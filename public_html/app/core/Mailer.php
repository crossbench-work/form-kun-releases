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

        $headers = self::buildHeaders($extraHeaders);
        if ($headers === null) {
            return false;
        }

        if (self::shouldUseSmtp()) {
            return self::sendViaSmtp($to, $subject, $body, $headers);
        }

        $fromEmail = self::extractEnvelopeFrom($headers['From'] ?? '');
        $additional = '';
        foreach ($headers as $k => $v) {
            if (in_array(strtolower($k), ['mime-version', 'content-type', 'content-transfer-encoding'], true)) {
                continue;
            }
            $additional .= $k . ': ' . $v . "\r\n";
        }

        if (function_exists('mb_send_mail')) {
            $sent = mb_send_mail($to, $subject, $body, $additional, '-f' . $fromEmail);
            if (!$sent) {
                error_log('[formkun] mailer: mb_send_mail failed');
            }
            return $sent;
        }

        $allHeaders = '';
        foreach ($headers as $k => $v) {
            $allHeaders .= $k . ': ' . $v . "\r\n";
        }
        $sent = mail($to, self::encodeMimeHeader($subject), $body, $allHeaders, '-f' . $fromEmail);
        if (!$sent) {
            error_log('[formkun] mailer: mail fallback failed');
        }
        return $sent;
    }

    private static function shouldUseSmtp(): bool
    {
        $transport = strtolower(trim((string) config('mail.transport', 'mail')));
        return $transport === 'smtp' || !empty(config('mail.smtp.enabled', false));
    }

    private static function sendViaSmtp(string $to, string $subject, string $body, array $headers): bool
    {
        $socket = self::openSmtpSocket();
        if (!is_resource($socket)) {
            return false;
        }

        try {
            self::expectSmtpResponse($socket, [220]);

            $ehloHost = trim((string) config('mail.smtp.ehlo_host', $_SERVER['SERVER_NAME'] ?? 'localhost'));
            if ($ehloHost === '') {
                $ehloHost = 'localhost';
            }

            self::writeSmtpCommand($socket, 'EHLO ' . $ehloHost);
            self::expectSmtpResponse($socket, [250]);

            $secure = strtolower(trim((string) config('mail.smtp.secure', '')));
            if ($secure === 'tls') {
                self::writeSmtpCommand($socket, 'STARTTLS');
                self::expectSmtpResponse($socket, [220]);

                if (!defined('STREAM_CRYPTO_METHOD_TLS_CLIENT')) {
                    throw new \RuntimeException('この PHP 環境は STARTTLS に対応していません。');
                }

                $cryptoEnabled = stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                if ($cryptoEnabled !== true) {
                    throw new \RuntimeException('STARTTLS の開始に失敗しました。');
                }

                self::writeSmtpCommand($socket, 'EHLO ' . $ehloHost);
                self::expectSmtpResponse($socket, [250]);
            }

            $username = trim((string) config('mail.smtp.username', ''));
            $password = (string) config('mail.smtp.password', '');
            if ($username !== '') {
                self::writeSmtpCommand($socket, 'AUTH LOGIN');
                self::expectSmtpResponse($socket, [334]);
                self::writeSmtpCommand($socket, base64_encode($username));
                self::expectSmtpResponse($socket, [334]);
                self::writeSmtpCommand($socket, base64_encode($password));
                self::expectSmtpResponse($socket, [235]);
            }

            $fromEmail = self::extractEnvelopeFrom($headers['From'] ?? '');
            if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException('SMTP の送信元メールアドレスが不正です。');
            }

            self::writeSmtpCommand($socket, 'MAIL FROM:<' . $fromEmail . '>');
            self::expectSmtpResponse($socket, [250]);
            self::writeSmtpCommand($socket, 'RCPT TO:<' . $to . '>');
            self::expectSmtpResponse($socket, [250, 251]);
            self::writeSmtpCommand($socket, 'DATA');
            self::expectSmtpResponse($socket, [354]);

            $message = self::buildSmtpMessage($to, $subject, $body, $headers);
            $bytesWritten = fwrite($socket, $message . "\r\n.\r\n");
            if ($bytesWritten === false) {
                throw new \RuntimeException('SMTP メッセージ本文の送信に失敗しました。');
            }
            self::expectSmtpResponse($socket, [250]);

            self::writeSmtpCommand($socket, 'QUIT');
            self::expectSmtpResponse($socket, [221]);

            return true;
        } catch (\RuntimeException $e) {
            error_log('[formkun] mailer smtp: ' . $e->getMessage());
            return false;
        } finally {
            fclose($socket);
        }
    }

    /**
     * @return resource|null
     */
    private static function openSmtpSocket()
    {
        $host = trim((string) config('mail.smtp.host', ''));
        $port = (int) config('mail.smtp.port', 25);
        $secure = strtolower(trim((string) config('mail.smtp.secure', '')));
        $timeout = (int) config('mail.smtp.timeout', 10);

        if ($host === '' || $port <= 0) {
            error_log('[formkun] mailer smtp: host または port が未設定です。');
            return null;
        }
        if (!in_array($secure, ['', 'ssl', 'tls'], true)) {
            error_log('[formkun] mailer smtp: secure は "", "ssl", "tls" のいずれかを指定してください。');
            return null;
        }

        $transport = $secure === 'ssl' ? 'ssl' : 'tcp';
        $remote = $transport . '://' . $host . ':' . $port;
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => !empty(config('mail.smtp.verify_peer', true)),
                'verify_peer_name' => !empty(config('mail.smtp.verify_peer_name', true)),
                'allow_self_signed' => !empty(config('mail.smtp.allow_self_signed', false)),
            ],
        ]);

        $errno = 0;
        $errstr = '';
        $socket = stream_socket_client($remote, $errno, $errstr, max(1, $timeout), STREAM_CLIENT_CONNECT, $context);
        if (!is_resource($socket)) {
            error_log('[formkun] mailer smtp: 接続に失敗しました。' . $errstr . ' (' . $errno . ')');
            return null;
        }

        stream_set_timeout($socket, max(1, $timeout));
        return $socket;
    }

    private static function writeSmtpCommand($socket, string $command): void
    {
        $bytesWritten = fwrite($socket, $command . "\r\n");
        if ($bytesWritten === false) {
            throw new \RuntimeException('SMTP コマンド送信に失敗しました。');
        }
    }

    /**
     * @param array<int, int> $expectedCodes
     */
    private static function expectSmtpResponse($socket, array $expectedCodes): string
    {
        $response = self::readSmtpResponse($socket);
        $code = (int) substr($response, 0, 3);
        if (!in_array($code, $expectedCodes, true)) {
            throw new \RuntimeException('SMTP 応答が不正です。受信: ' . trim($response));
        }
        return $response;
    }

    private static function readSmtpResponse($socket): string
    {
        $response = '';
        while (($line = fgets($socket, 515)) !== false) {
            $response .= $line;
            if (strlen($line) < 4 || $line[3] === ' ') {
                break;
            }
        }

        if ($response === '') {
            throw new \RuntimeException('SMTP 応答を受信できませんでした。');
        }

        $meta = stream_get_meta_data($socket);
        if (!empty($meta['timed_out'])) {
            throw new \RuntimeException('SMTP 応答がタイムアウトしました。');
        }

        return $response;
    }

    private static function buildSmtpMessage(string $to, string $subject, string $body, array $headers): string
    {
        $messageHeaders = array_merge(
            [
                'To' => $to,
                'Subject' => self::encodeMimeHeader($subject),
                'Date' => date(DATE_RFC2822),
            ],
            $headers
        );

        $lines = [];
        foreach ($messageHeaders as $k => $v) {
            $lines[] = $k . ': ' . $v;
        }

        return implode("\r\n", $lines) . "\r\n\r\n" . self::normalizeMessageBody($body);
    }

    private static function normalizeMessageBody(string $body): string
    {
        $normalized = str_replace(["\r\n", "\r"], "\n", $body);
        $lines = explode("\n", $normalized);
        foreach ($lines as &$line) {
            if (strpos($line, '.') === 0) {
                $line = '.' . $line;
            }
        }
        unset($line);

        return implode("\r\n", $lines);
    }

    /**
     * @return array<string, string>|null
     */
    private static function buildHeaders(array $extraHeaders): ?array
    {
        $headers = array_merge([
            'From' => self::buildDefaultFromHeader(),
            'MIME-Version' => '1.0',
            'Content-Type' => 'text/plain; charset=UTF-8',
        ], $extraHeaders);

        $normalized = [];
        foreach ($headers as $k => $v) {
            $name = trim((string) $k);
            $value = trim((string) $v);
            if ($name === '' || $value === '') {
                continue;
            }
            if (!preg_match('/\A[A-Za-z0-9\-]+\z/', $name)) {
                error_log('[formkun] mailer: invalid header name blocked');
                return null;
            }
            if (preg_match('/[\r\n]/', $value)) {
                error_log('[formkun] mailer: header injection blocked');
                return null;
            }
            $normalized[$name] = $value;
        }

        return $normalized;
    }

    private static function buildDefaultFromHeader(): string
    {
        $fromEmail = trim((string) config('mail.from_email', 'noreply@example.com'));
        $fromName = trim((string) config('mail.from_name', ''));
        if ($fromName === '') {
            return $fromEmail;
        }

        return self::encodeMimeHeader($fromName) . ' <' . $fromEmail . '>';
    }

    private static function extractEnvelopeFrom(string $fromHeader): string
    {
        if (preg_match('/<([^>]+)>/', $fromHeader, $matches) === 1) {
            return trim((string) $matches[1]);
        }

        $candidate = trim($fromHeader);
        if (filter_var($candidate, FILTER_VALIDATE_EMAIL)) {
            return $candidate;
        }

        return trim((string) config('mail.from_email', 'noreply@example.com'));
    }

    private static function encodeMimeHeader(string $value): string
    {
        if ($value === '') {
            return '';
        }

        if (preg_match('/\A[\x20-\x7E]+\z/', $value) === 1) {
            return $value;
        }

        return '=?UTF-8?B?' . base64_encode($value) . '?=';
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
