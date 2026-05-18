<?php
namespace Controllers;

/**
 * 郵便番号から住所を返す（ZipCloud API のサーバ側プロキシ。フロントの CORS を回避）。
 */
class PostalController
{
    public function lookup(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $zip = preg_replace('/\D/', '', (string) ($_GET['zip'] ?? ''));
        if (strlen($zip) !== 7) {
            echo json_encode(['ok' => false, 'message' => '郵便番号は7桁の数字で入力してください。'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $url = 'https://zipcloud.ibsnet.co.jp/api/search?zipcode=' . rawurlencode($zip);
        $raw = self::httpGet($url);
        if ($raw === null || $raw === '') {
            echo json_encode(['ok' => false, 'message' => '住所検索に失敗しました。時間をおいて再度お試しください。'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $data = json_decode($raw, true);
        if (!is_array($data) || (int) ($data['status'] ?? 0) !== 200) {
            $msg = isset($data['message']) && is_string($data['message']) ? $data['message'] : '該当する住所が見つかりません。';
            echo json_encode(['ok' => false, 'message' => $msg], JSON_UNESCAPED_UNICODE);
            return;
        }

        $results = $data['results'] ?? null;
        if (!is_array($results) || count($results) === 0) {
            echo json_encode(['ok' => false, 'message' => '該当する住所が見つかりません。'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $r = $results[0];
        // ZipCloud は都道府県を address1 に含める。prefecture キーは通常ない。
        $prefecture = trim((string) ($r['prefecture'] ?? $r['address1'] ?? ''));
        $city = trim(
            (string) ($r['address2'] ?? '')
            . (string) ($r['address3'] ?? '')
        );

        echo json_encode([
            'ok'         => true,
            'prefecture' => $prefecture,
            'city'       => $city,
        ], JSON_UNESCAPED_UNICODE);
    }

    private static function httpGet(string $url): ?string
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            if ($ch !== false) {
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_TIMEOUT        => 10,
                    CURLOPT_HTTPHEADER     => [
                        'Accept: application/json',
                        'User-Agent: form-kun/1',
                    ],
                ]);
                $raw = curl_exec($ch);
                $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
                curl_close($ch);
                if (is_string($raw) && $raw !== '' && ($code === 0 || ($code >= 200 && $code < 300))) {
                    return $raw;
                }
            }
        }

        $fopenEnabled = filter_var(ini_get('allow_url_fopen'), FILTER_VALIDATE_BOOLEAN);
        if (!$fopenEnabled) {
            return null;
        }
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 8,
                'header'  => "Accept: application/json\r\nUser-Agent: form-kun/1\r\n",
            ],
        ]);
        $raw = @file_get_contents($url, false, $ctx);
        if ($raw !== false && $raw !== '') {
            return $raw;
        }
        return null;
    }
}
