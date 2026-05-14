<?php
namespace Core;

class Mailer
{
    /**
     * メール送信。複数宛先はカンマ区切り or 配列。
     */
    public static function send($to, string $subject, string $body, array $extraHeaders = []): bool
    {
        $recipients = [];
        if (is_array($to)) {
            foreach ($to as $address) {
                $address = trim((string) $address);
                if ($address !== '') {
                    $recipients[] = $address;
                }
            }
        } else {
            $recipients = array_filter(array_map('trim', preg_split('/[,;]+/', (string) $to) ?: []));
        }
        if ($recipients === []) {
            return false;
        }

        $sent = false;
        foreach ($recipients as $recipient) {
            $sent = self::sendSingle($recipient, $subject, $body, $extraHeaders) || $sent;
        }

        return $sent;
    }

    private static function sendSingle(string $to, string $subject, string $body, array $extraHeaders = []): bool
    {
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
        $typesByKey = [];
        foreach ($fields as $f) {
            $typesByKey[$f['field_key']] = $f['type'];
        }

        $allLines = [];
        foreach ($fields as $f) {
            $val = $values[$f['field_key']] ?? '';
            if ($f['type'] === 'file') {
                $name = is_string($val) ? $val : (is_array($val) && !empty($val['name']) ? $val['name'] : '');
                $allLines[] = $f['label'] . ': ' . ($name !== '' ? $name : '（ファイルなし）');
                continue;
            }
            if ($f['type'] === 'address') {
                $valStr = \format_address_value_display($val);
                $allLines[] = $f['label'] . ":\n" . $valStr;
                continue;
            }
            if ($f['type'] === 'text_pair') {
                $pairLabels = \Models\FormField::decodeTextPairLabels($f['options_json'] ?? null);
                $valStr = \format_text_pair_value_display($val, $pairLabels);
                $allLines[] = $f['label'] . ":\n" . $valStr;
                continue;
            }
            if ($f['type'] === 'text_multi') {
                $multiItems = \Models\FormField::decodeTextMultiItems($f['options_json'] ?? null, true);
                $valStr = \format_text_multi_value_display($val, $multiItems);
                $allLines[] = $f['label'] . ":\n" . $valStr;
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
            if (($typesByKey[$key] ?? '') === 'address') {
                $val = \format_address_value_display($val);
            } elseif (($typesByKey[$key] ?? '') === 'text_pair') {
                $field = null;
                foreach ($fields as $f) {
                    if (($f['field_key'] ?? '') === $key) {
                        $field = $f;
                        break;
                    }
                }
                $pairLabels = \Models\FormField::decodeTextPairLabels($field['options_json'] ?? null);
                $val = \format_text_pair_value_display($val, $pairLabels);
            } elseif (($typesByKey[$key] ?? '') === 'text_multi') {
                $field = null;
                foreach ($fields as $f) {
                    if (($f['field_key'] ?? '') === $key) {
                        $field = $f;
                        break;
                    }
                }
                $multiItems = \Models\FormField::decodeTextMultiItems($field['options_json'] ?? null, true);
                $val = \format_text_multi_value_display($val, $multiItems);
            } elseif (is_array($val)) {
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
