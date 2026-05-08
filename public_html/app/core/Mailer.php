<?php
namespace Core;

class Mailer
{
    /**
     * メール送信。複数宛先はカンマ区切り or 配列。
     */
    public static function send($to, string $subject, string $body, array $extraHeaders = []): bool
    {
        if (is_array($to)) {
            $to = implode(',', $to);
        }
        $to = trim($to);
        if ($to === '') {
            return false;
        }

        if (function_exists('mb_language')) {
            mb_language('Japanese');
        }
        if (function_exists('mb_internal_encoding')) {
            mb_internal_encoding('UTF-8');
        }

        $fromEmail = (string) config('mail.from_email', 'noreply@example.com');
        $fromName  = (string) config('mail.from_name', '');
        $fromHeader = $fromName !== ''
            ? sprintf('%s <%s>', mb_encode_mimeheader($fromName, 'UTF-8'), $fromEmail)
            : $fromEmail;

        $headers = array_merge([
            'From'         => $fromHeader,
            'MIME-Version' => '1.0',
            'Content-Type' => 'text/plain; charset=UTF-8',
        ], $extraHeaders);

        // mb_send_mail は MIME-Version / Content-Type を自分で付与するため二重指定しないよう注意。
        // ヘッダ整形（CRLF/制御文字混入チェック）
        foreach ($headers as $k => $v) {
            if (preg_match('/[\r\n]/', $v)) {
                error_log('[formkun] mailer: header injection blocked');
                return false;
            }
        }
        $additional = '';
        foreach ($headers as $k => $v) {
            if (in_array(strtolower($k), ['mime-version', 'content-type', 'content-transfer-encoding'], true)) {
                continue; // mb_send_mail に任せる
            }
            $additional .= $k . ': ' . $v . "\r\n";
        }

        if (function_exists('mb_send_mail')) {
            return @mb_send_mail($to, $subject, $body, $additional, '-f' . $fromEmail);
        }

        // フォールバック
        $allHeaders = '';
        foreach ($headers as $k => $v) {
            $allHeaders .= $k . ': ' . $v . "\r\n";
        }
        return @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, $allHeaders, '-f' . $fromEmail);
    }

    /**
     * テンプレート内の {{field_key}} を置換。
     * 特別キー: {{__all__}} = 全項目を「ラベル: 値」改行区切り
     *           {{__submitted_at__}} {{__form_title__}} {{__ip__}} など。
     *
     * @param array<int, array> $fields
     * @param array<string, mixed> $values
     */
    public static function renderTemplate(string $template, array $fields, array $values, array $context = []): string
    {
        $allLines = [];
        foreach ($fields as $f) {
            $val = $values[$f['field_key']] ?? '';
            if ($f['type'] === 'file') {
                $name = is_string($val) ? $val : (is_array($val) && !empty($val['name']) ? $val['name'] : '');
                $allLines[] = $f['label'] . ': ' . ($name !== '' ? $name : '（ファイルなし）');
                continue;
            }
            if (is_array($val)) {
                $val = implode(', ', $val);
            }
            $allLines[] = $f['label'] . ":\n" . $val;
        }
        $allText = implode("\n----------------------------------\n", $allLines);

        $replacements = [
            '{{__all__}}'           => $allText,
            '{{__submitted_at__}}'  => $context['submitted_at'] ?? date('Y-m-d H:i:s'),
            '{{__form_title__}}'    => $context['form_title'] ?? '',
            '{{__ip__}}'            => $context['ip'] ?? '',
        ];

        foreach ($values as $key => $val) {
            if (is_array($val)) {
                if (isset($val['name'])) {
                    $val = (string) $val['name'];
                } else {
                    $val = implode(', ', $val);
                }
            }
            $replacements['{{' . $key . '}}'] = (string) $val;
        }

        return strtr($template, $replacements);
    }
}
