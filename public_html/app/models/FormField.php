<?php
namespace Models;

use Core\Database;

class FormField
{
    /**
     * DB の regex 列（JSON 配列または従来の1本文字列）をパターン配列に。
     *
     * @return string[]
     */
    public static function decodeRegexPatterns(?string $stored): array
    {
        if ($stored === null) {
            return [];
        }
        $stored = trim($stored);
        if ($stored === '') {
            return [];
        }
        $decoded = json_decode($stored, true);
        if (is_array($decoded) && array_keys($decoded) === range(0, count($decoded) - 1)) {
            $out = [];
            foreach ($decoded as $p) {
                if (!is_string($p)) {
                    continue;
                }
                $p = trim($p);
                if ($p !== '') {
                    $out[] = $p;
                }
            }
            return $out;
        }

        return [$stored];
    }

    /**
     * @param string[] $patterns
     */
    public static function encodeRegexPatterns(array $patterns): ?string
    {
        $clean = [];
        foreach ($patterns as $p) {
            $p = trim((string) $p);
            if ($p !== '') {
                $clean[] = $p;
            }
        }
        if (count($clean) === 0) {
            return null;
        }

        return json_encode(array_values($clean), JSON_UNESCAPED_UNICODE);
    }

    /**
     * 入力タイプの内部値 => 管理画面・一覧向け日本語ラベル（表示順維持）。
     *
     * @return array<string, string>
     */
    public static function TYPE_LABELS(): array
    {
        return [
            'text' => '1行テキスト',
            'text_pair' => '1行テキスト（2入力）',
            'textarea' => '複数行テキスト',
            'address' => '住所',
            'email' => 'メールアドレス',
            'tel' => '電話番号',
            'number' => '数値',
            'date' => '日付',
            'time' => '時間（時:分）',
            'radio' => 'ラジオボタン',
            'checkbox' => 'チェックボックス',
            'select' => 'プルダウン（選択）',
            'file' => 'ファイル添付',
        ];
    }

    public static function ALLOWED_TYPES(): array
    {
        return array_keys(self::TYPE_LABELS());
    }

    public static function typeLabel(string $type): string
    {
        $labels = self::TYPE_LABELS();

        return $labels[$type] ?? $type;
    }

    public static function forForm(int $formId): array
    {
        return Database::fetchAll(
            'SELECT * FROM form_fields WHERE form_id = ? ORDER BY sort_order ASC, id ASC',
            [$formId]
        );
    }

    /**
     * フォームの項目をまるごと置き換え。
     *
     * @param array<int, array> $fields
     */
    public static function replaceForForm(int $formId, array $fields): void
    {
        Database::transaction(function () use ($formId, $fields) {
            Database::execute('DELETE FROM form_fields WHERE form_id = ?', [$formId]);
            $order = 0;
            foreach ($fields as $f) {
                $order++;
                $optionsJson = null;
                if (in_array($f['type'], ['radio','checkbox','select'], true) && !empty($f['options'])) {
                    $opts = self::parseOptions($f['options']);
                    $optionsJson = json_encode($opts, JSON_UNESCAPED_UNICODE);
                } elseif ($f['type'] === 'text_pair') {
                    $optionsJson = self::encodeTextPairLabels(
                        (string) ($f['pair_label_left'] ?? ''),
                        (string) ($f['pair_label_right'] ?? '')
                    );
                }
                $placeholder = isset($f['placeholder']) && $f['placeholder'] !== '' ? (string) $f['placeholder'] : null;
                if (in_array($f['type'], ['radio', 'checkbox', 'select'], true)) {
                    $placeholder = null;
                }
                if ($f['type'] === 'text_pair') {
                    $placeholder = self::encodeTextPairPlaceholders(
                        (string) ($f['pair_placeholder_left'] ?? ''),
                        (string) ($f['pair_placeholder_right'] ?? '')
                    );
                }
                $maxLength = isset($f['max_length']) && $f['max_length'] !== '' ? (int) $f['max_length'] : null;
                if (in_array($f['type'], ['radio', 'checkbox', 'select'], true)) {
                    $maxLength = null;
                }
                Database::execute(
                    'INSERT INTO form_fields (
                        form_id, sort_order, field_key, label, type,
                        required, max_length, regex, help_text, placeholder, options_json
                     ) VALUES (?,?,?,?,?,?,?,?,?,?,?)',
                    [
                        $formId,
                        $order,
                        $f['field_key'],
                        $f['label'],
                        $f['type'],
                        !empty($f['required']) ? 1 : 0,
                        $maxLength,
                        isset($f['regex']) && $f['regex'] !== null && $f['regex'] !== ''
                            ? (string) $f['regex']
                            : null,
                        isset($f['help_text']) && $f['help_text'] !== '' ? (string) $f['help_text'] : null,
                        $placeholder,
                        $optionsJson,
                    ]
                );
            }
        });
    }

    /**
     * "選択肢A\n選択肢B" 形式 / 配列両方を受け付け、配列に正規化。
     */
    public static function parseOptions($value): array
    {
        if (is_array($value)) {
            $arr = $value;
        } elseif (is_string($value)) {
            $arr = preg_split('/\r\n|\r|\n/', $value) ?: [];
        } else {
            return [];
        }
        $out = [];
        foreach ($arr as $v) {
            $v = trim((string) $v);
            if ($v !== '') {
                $out[] = $v;
            }
        }
        return array_values(array_unique($out));
    }

    /**
     * @return array{left_label:string,right_label:string}
     */
    public static function decodeTextPairLabels($json): array
    {
        if (!is_string($json) || trim($json) === '') {
            return ['left_label' => '', 'right_label' => ''];
        }

        $decoded = json_decode($json, true);
        if (!is_array($decoded)) {
            return ['left_label' => '', 'right_label' => ''];
        }

        if (array_keys($decoded) === range(0, count($decoded) - 1)) {
            return [
                'left_label' => isset($decoded[0]) ? trim((string) $decoded[0]) : '',
                'right_label' => isset($decoded[1]) ? trim((string) $decoded[1]) : '',
            ];
        }

        return [
            'left_label' => trim((string) ($decoded['left_label'] ?? '')),
            'right_label' => trim((string) ($decoded['right_label'] ?? '')),
        ];
    }

    public static function encodeTextPairLabels(string $leftLabel, string $rightLabel): ?string
    {
        $payload = [
            'left_label' => trim($leftLabel),
            'right_label' => trim($rightLabel),
        ];
        if ($payload['left_label'] === '' && $payload['right_label'] === '') {
            return null;
        }

        return json_encode($payload, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return array{left_placeholder:string,right_placeholder:string}
     */
    public static function decodeTextPairPlaceholders($stored): array
    {
        if (!is_string($stored) || trim($stored) === '') {
            return ['left_placeholder' => '', 'right_placeholder' => ''];
        }

        $decoded = json_decode($stored, true);
        if (!is_array($decoded)) {
            return [
                'left_placeholder' => trim($stored),
                'right_placeholder' => '',
            ];
        }

        if (array_keys($decoded) === range(0, count($decoded) - 1)) {
            return [
                'left_placeholder' => isset($decoded[0]) ? trim((string) $decoded[0]) : '',
                'right_placeholder' => isset($decoded[1]) ? trim((string) $decoded[1]) : '',
            ];
        }

        return [
            'left_placeholder' => trim((string) ($decoded['left_placeholder'] ?? '')),
            'right_placeholder' => trim((string) ($decoded['right_placeholder'] ?? '')),
        ];
    }

    public static function encodeTextPairPlaceholders(string $leftPlaceholder, string $rightPlaceholder): ?string
    {
        $payload = [
            'left_placeholder' => trim($leftPlaceholder),
            'right_placeholder' => trim($rightPlaceholder),
        ];
        if ($payload['left_placeholder'] === '' && $payload['right_placeholder'] === '') {
            return null;
        }

        return json_encode($payload, JSON_UNESCAPED_UNICODE);
    }
}
