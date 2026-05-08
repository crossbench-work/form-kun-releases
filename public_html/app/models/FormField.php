<?php
namespace Models;

use Core\Database;

class FormField
{
    public static function ALLOWED_TYPES(): array
    {
        return ['text','textarea','email','tel','number','radio','checkbox','select','date','time','file'];
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
                        isset($f['max_length']) && $f['max_length'] !== '' ? (int) $f['max_length'] : null,
                        isset($f['regex']) && $f['regex'] !== '' ? (string) $f['regex'] : null,
                        isset($f['help_text']) && $f['help_text'] !== '' ? (string) $f['help_text'] : null,
                        isset($f['placeholder']) && $f['placeholder'] !== '' ? (string) $f['placeholder'] : null,
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
}
