<?php
namespace Core;

/**
 * Git update ブランチの manifest / ZIP からアプリ本体を更新（共有レンタル向け）。
 */
class AppUpdater
{
    private const LOG_FILE = 'update.log';

    public static function isEnabled(): bool
    {
        return (bool) config('update.enabled', false);
    }

    public static function currentVersion(): string
    {
        $file = APP_DIR . '/version.php';
        if (is_file($file)) {
            $v = require $file;
            if (is_string($v) && $v !== '') {
                return $v;
            }
        }
        return '0.0.0';
    }

    /**
     * @return array{ok:bool,message:string,manifest?:array}
     */
    public static function fetchManifest(): array
    {
        if (!self::isEnabled()) {
            return ['ok' => false, 'message' => '更新機能が無効です。'];
        }
        $url = trim((string) config('update.manifest_url', ''));
        if ($url === '') {
            return ['ok' => false, 'message' => 'manifest_url が未設定です。'];
        }
        try {
            self::assertAllowedUrl($url);
            $body = self::httpGet($url);
            $data = json_decode($body, true);
            if (!is_array($data) || empty($data['releases']) || !is_array($data['releases'])) {
                return ['ok' => false, 'message' => 'manifest の形式が不正です。'];
            }
            return ['ok' => true, 'message' => '', 'manifest' => $data];
        } catch (\Throwable $e) {
            self::log('manifest error: ' . $e->getMessage());
            return ['ok' => false, 'message' => 'manifest の取得に失敗しました。'];
        }
    }

    /**
     * @return array{ok:bool,message:string}
     */
    public static function applyRelease(string $version): array
    {
        if (!self::isEnabled()) {
            return ['ok' => false, 'message' => '更新機能が無効です。'];
        }
        if (!class_exists(\ZipArchive::class)) {
            return ['ok' => false, 'message' => 'ZipArchive 拡張が必要です。'];
        }

        $manifestResult = self::fetchManifest();
        if (!$manifestResult['ok']) {
            return ['ok' => false, 'message' => $manifestResult['message']];
        }
        $manifest = $manifestResult['manifest'];
        $release = null;
        foreach ($manifest['releases'] as $row) {
            if (is_array($row) && ($row['version'] ?? '') === $version) {
                $release = $row;
                break;
            }
        }
        if ($release === null) {
            return ['ok' => false, 'message' => '指定バージョンが manifest にありません。'];
        }

        $zipUrl = (string) ($release['zip_url'] ?? '');
        $expectedSha = strtolower((string) ($release['sha256'] ?? ''));
        if ($zipUrl === '' || $expectedSha === '') {
            return ['ok' => false, 'message' => 'リリース情報が不足しています。'];
        }

        self::acquireMaintenanceLock();
        try {
            @set_time_limit(300);
            self::assertAllowedUrl($zipUrl);
            $tmpZip = self::downloadToTemp($zipUrl);
            $actualSha = hash_file('sha256', $tmpZip);
            if (!hash_equals($expectedSha, strtolower($actualSha))) {
                @unlink($tmpZip);
                return ['ok' => false, 'message' => 'ZIP の sha256 が一致しません。'];
            }

            $staging = PUBLIC_DIR . '/.update-staging-' . preg_replace('/[^a-zA-Z0-9._-]/', '', $version);
            if (is_dir($staging)) {
                self::removeDirectory($staging);
            }
            mkdir($staging, 0755, true);

            $zip = new \ZipArchive();
            if ($zip->open($tmpZip) !== true) {
                @unlink($tmpZip);
                return ['ok' => false, 'message' => 'ZIP を開けませんでした。'];
            }
            if (!$zip->extractTo($staging)) {
                $zip->close();
                @unlink($tmpZip);
                return ['ok' => false, 'message' => 'ZIP の展開に失敗しました。'];
            }
            $zip->close();
            @unlink($tmpZip);

            self::copyTree($staging, PUBLIC_DIR, self::protectedPatterns());
            self::removeDirectory($staging);

            $versionFile = APP_DIR . '/version.php';
            $escaped = str_replace("'", "\\'", $version);
            file_put_contents(
                $versionFile,
                "<?php\nif (!defined('FORMKUN_BOOT')) { http_response_code(404); exit; }\nreturn '" . $escaped . "';\n"
            );

            self::log('applied ' . $version);
            return ['ok' => true, 'message' => 'バージョン ' . $version . ' へ更新しました。'];
        } catch (\Throwable $e) {
            self::log('apply error: ' . $e->getMessage());
            return ['ok' => false, 'message' => '更新中にエラーが発生しました。'];
        } finally {
            self::releaseMaintenanceLock();
        }
    }

    public static function requirements(): array
    {
        return [
            'zip' => class_exists(\ZipArchive::class),
            'enabled' => self::isEnabled(),
            'manifest_url' => trim((string) config('update.manifest_url', '')) !== '',
        ];
    }

    public static function isMaintenanceActive(): bool
    {
        return is_file(self::lockPath());
    }

    public static function maintenanceBlocksRequest(): bool
    {
        if (!self::isMaintenanceActive()) {
            return false;
        }
        $uri = (string) ($_SERVER['REQUEST_URI'] ?? '');
        if (strpos($uri, '/admin/settings/update') !== false) {
            return false;
        }
        return true;
    }

    private static function lockPath(): string
    {
        return PUBLIC_DIR . '/storage/maintenance.lock';
    }

    private static function acquireMaintenanceLock(): void
    {
        if (!is_dir(PUBLIC_DIR . '/storage')) {
            mkdir(PUBLIC_DIR . '/storage', 0755, true);
        }
        file_put_contents(self::lockPath(), date('c'));
    }

    private static function releaseMaintenanceLock(): void
    {
        $p = self::lockPath();
        if (is_file($p)) {
            @unlink($p);
        }
    }

  /** @return string[] */
    private static function protectedPatterns(): array
    {
        $file = APP_DIR . '/config/protected-paths.json';
        if (is_file($file)) {
            $json = json_decode((string) file_get_contents($file), true);
            if (is_array($json['never_overwrite_on_update'] ?? null)) {
                return $json['never_overwrite_on_update'];
            }
        }
        return ['app/config.local.php', 'uploads'];
    }

    private static function isProtectedRelative(string $rel): bool
    {
        $rel = str_replace('\\', '/', $rel);
        foreach (self::protectedPatterns() as $pattern) {
            $p = str_replace('\\', '/', $pattern);
            if ($rel === $p || str_starts_with($rel, rtrim($p, '/') . '/')) {
                return true;
            }
        }
        return false;
    }

    private static function copyTree(string $src, string $dest, array $protected): void
    {
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($src, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($it as $item) {
            $sub = $it->getSubPathname();
            $sub = str_replace('\\', '/', $sub);
            if (self::isProtectedRelative($sub)) {
                continue;
            }
            $target = $dest . '/' . $sub;
            if ($item->isDir()) {
                if (!is_dir($target)) {
                    mkdir($target, 0755, true);
                }
            } else {
                $dir = dirname($target);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                copy($item->getPathname(), $target);
            }
        }
    }

    private static function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $item) {
            if ($item->isDir()) {
                @rmdir($item->getPathname());
            } else {
                @unlink($item->getPathname());
            }
        }
        @rmdir($dir);
    }

    private static function assertAllowedUrl(string $url): void
    {
        $parts = parse_url($url);
        if (($parts['scheme'] ?? '') !== 'https') {
            throw new \RuntimeException('HTTPS のみ許可');
        }
        $host = strtolower((string) ($parts['host'] ?? ''));
        $allowed = config('update.allowed_hosts', ['raw.githubusercontent.com']);
        if (!is_array($allowed)) {
            $allowed = ['raw.githubusercontent.com'];
        }
        $ok = false;
        foreach ($allowed as $h) {
            if ($host === strtolower((string) $h)) {
                $ok = true;
                break;
            }
        }
        if (!$ok) {
            throw new \RuntimeException('許可されていないホスト');
        }
    }

    private static function httpGet(string $url): string
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_SSL_VERIFYPEER => true,
            ]);
            $body = curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($body === false || $code < 200 || $code >= 300) {
                throw new \RuntimeException('HTTP ' . $code);
            }
            return (string) $body;
        }
        $ctx = stream_context_create([
            'http' => ['timeout' => 60],
            'ssl' => ['verify_peer' => true, 'verify_peer_name' => true],
        ]);
        $body = @file_get_contents($url, false, $ctx);
        if ($body === false) {
            throw new \RuntimeException('download failed');
        }
        return $body;
    }

    private static function downloadToTemp(string $url): string
    {
        $tmp = tempnam(sys_get_temp_dir(), 'formkun');
        if ($tmp === false) {
            throw new \RuntimeException('temp file');
        }
        $body = self::httpGet($url);
        file_put_contents($tmp, $body);
        return $tmp;
    }

    private static function log(string $line): void
    {
        $dir = PUBLIC_DIR . '/storage';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $path = $dir . '/' . self::LOG_FILE;
        @file_put_contents($path, date('c') . ' ' . $line . "\n", FILE_APPEND);
    }
}
