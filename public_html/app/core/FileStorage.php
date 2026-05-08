<?php
namespace Core;

class FileStorage
{
    /**
     * 一時アップロードファイルを uploads/yyyymm/ に保存し、メタ情報を返す。
     *
     * @return array{stored_path:string, original_name:string, mime_type:string, size:int}|null
     */
    public static function store(array $entry): ?array
    {
        if (!isset($entry['tmp_name']) || $entry['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($entry['tmp_name'])) {
            return null;
        }

        $sub = date('Ym');
        $dir = UPLOADS_DIR . DIRECTORY_SEPARATOR . $sub;
        if (!is_dir($dir)) {
            if (!@mkdir($dir, 0775, true) && !is_dir($dir)) {
                throw new \RuntimeException('uploads ディレクトリを作成できません: ' . $dir);
            }
        }

        $original = self::sanitizeName((string) ($entry['name'] ?? 'upload.bin'));
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $hash = bin2hex(random_bytes(12));
        $stored = $hash . ($ext !== '' ? '.' . $ext : '');
        $absPath = $dir . DIRECTORY_SEPARATOR . $stored;

        if (!@move_uploaded_file($entry['tmp_name'], $absPath)) {
            throw new \RuntimeException('アップロードファイルの保存に失敗しました。');
        }
        @chmod($absPath, 0644);

        $mime = '';
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = (string) finfo_file($finfo, $absPath);
            finfo_close($finfo);
        }

        return [
            'stored_path'   => $sub . '/' . $stored,
            'original_name' => $original,
            'mime_type'     => $mime,
            'size'          => filesize($absPath) ?: 0,
        ];
    }

    public static function absolutePath(string $relPath): string
    {
        $rel = ltrim(str_replace(['..', "\0"], '', $relPath), '/\\');
        return UPLOADS_DIR . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    }

    public static function delete(string $relPath): void
    {
        $abs = self::absolutePath($relPath);
        if (is_file($abs)) {
            @unlink($abs);
        }
    }

    private static function sanitizeName(string $name): string
    {
        $name = preg_replace('/[\\\\\/\x00-\x1f]/', '_', $name) ?? 'upload.bin';
        $name = preg_replace('/\s+/', '_', $name) ?? $name;
        if (strlen($name) > 200) {
            $name = substr($name, -200);
        }
        return $name;
    }
}
