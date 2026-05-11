<?php
namespace Core;

use Models\FormField;

class Validator
{
    /** @var array<string, array<string, mixed>> */
    private array $errors = [];

    /**
     * フォーム項目定義に基づいて入力をバリデーション。
     *
     * @param array<int, array> $fields  form_fields の配列
     * @param array<string, mixed> $input
     * @param array<string, array> $files  $_FILES 形式（fieldKey => entry）
     * @return array{values: array<string,mixed>, errors: array<string, mixed>}
     */
    public function validate(array $fields, array $input, array $files = []): array
    {
        $values = [];
        foreach ($fields as $field) {
            $key = $field['field_key'];
            $type = $field['type'];

            if ($type === 'file') {
                $entry = $files[$key] ?? null;
                // ファイル項目は submission_values には保存しない（submission_files で管理）
                // テンプレート用に元ファイル名だけは values に残す
                $values[$key] = is_array($entry) && !empty($entry['name']) ? (string) $entry['name'] : '';
                $this->validateFile($field, $entry);
                continue;
            }

            $raw = $input[$key] ?? null;
            $clean = $this->normalize($raw, $type);
            $values[$key] = $clean;
            $this->validateScalar($field, $clean);
        }

        return ['values' => $values, 'errors' => $this->errors];
    }

    private function normalize($raw, string $type)
    {
        if ($type === 'address') {
            if (!is_array($raw)) {
                return ['postal' => '', 'prefecture' => '', 'city' => '', 'number' => '', 'building' => ''];
            }
            $p3 = isset($raw['postal3']) ? preg_replace('/\D/', '', (string) $raw['postal3']) : '';
            $p4 = isset($raw['postal4']) ? preg_replace('/\D/', '', (string) $raw['postal4']) : '';
            $legacy = isset($raw['postal']) ? preg_replace('/\D/', '', (string) $raw['postal']) : '';
            $digits = ($p3 !== '' || $p4 !== '') ? ($p3 . $p4) : $legacy;
            $postal = '';
            if (strlen($digits) === 7) {
                $postal = substr($digits, 0, 3) . '-' . substr($digits, 3);
            } elseif ($digits !== '') {
                $postal = $digits;
            }
            $pref = isset($raw['prefecture']) ? trim((string) $raw['prefecture']) : '';
            $prefAllowed = array_flip(\japanese_prefectures());
            if ($pref !== '' && !isset($prefAllowed[$pref])) {
                $pref = '';
            }
            $number = isset($raw['number']) ? trim((string) $raw['number']) : '';
            $building = isset($raw['building']) ? trim((string) $raw['building']) : '';
            if ($number === '' && $building === '' && isset($raw['street'])) {
                $number = trim((string) $raw['street']);
            }
            return [
                'postal'      => $postal,
                'prefecture'  => $pref,
                'city'        => isset($raw['city']) ? trim((string) $raw['city']) : '',
                'number'      => $number,
                'building'    => $building,
            ];
        }
        if ($type === 'text_pair') {
            if (!is_array($raw)) {
                return ['left' => '', 'right' => ''];
            }
            return [
                'left' => isset($raw['left']) ? trim((string) $raw['left']) : '',
                'right' => isset($raw['right']) ? trim((string) $raw['right']) : '',
            ];
        }
        if (is_array($raw)) {
            $arr = [];
            foreach ($raw as $v) {
                if (is_string($v)) {
                    $arr[] = trim($v);
                }
            }
            return $arr;
        }
        if ($raw === null) {
            return $type === 'checkbox' ? [] : '';
        }
        if (is_string($raw)) {
            return trim($raw);
        }
        return '';
    }

    private function validateScalar(array $field, $value): void
    {
        $key   = $field['field_key'];
        $label = $field['label'];
        $type  = $field['type'];
        $req   = (int) ($field['required'] ?? 0) === 1;
        $maxL  = isset($field['max_length']) ? (int) $field['max_length'] : 0;
        $regexStored = $field['regex'] ?? '';
        $regexPatterns = is_string($regexStored)
            ? FormField::decodeRegexPatterns($regexStored)
            : [];
        $opts  = self::decodeOptions($field['options_json'] ?? null);

        if ($type === 'address') {
            $this->validateAddressField($field, $value);
            return;
        }
        if ($type === 'text_pair') {
            $this->validateTextPairField($field, $value);
            return;
        }

        $isEmpty = is_array($value) ? count($value) === 0 : ((string) $value === '');
        if ($req && $isEmpty) {
            $this->errors[$key][] = $label . 'は必須項目です。';
            return;
        }
        if ($isEmpty) {
            return;
        }

        if (is_string($value)) {
            if ($maxL > 0 && mb_strlen($value, 'UTF-8') > $maxL) {
                $this->errors[$key][] = $label . 'は' . $maxL . '文字以内で入力してください。';
            }
            foreach ($regexPatterns as $regex) {
                if ($regex === '') {
                    continue;
                }
                if (@preg_match($regex, '') === false) {
                    $this->errors[$key][] = $label . 'の検証設定が不正です。';
                    break;
                }
                if (!preg_match($regex, $value)) {
                    $this->errors[$key][] = $label . 'の形式が正しくありません。';
                    break;
                }
            }
        }

        switch ($type) {
            case 'email':
                if (is_string($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$key][] = $label . 'はメールアドレスの形式で入力してください。';
                }
                break;
            case 'tel':
                if (is_string($value) && !preg_match('/\A[0-9+\-\s()]{6,20}\z/', $value)) {
                    $this->errors[$key][] = $label . 'は電話番号の形式で入力してください。';
                }
                break;
            case 'number':
                if (is_string($value) && !is_numeric($value)) {
                    $this->errors[$key][] = $label . 'は数値で入力してください。';
                }
                break;
            case 'date':
                if (is_string($value) && !$this->isValidDate($value)) {
                    $this->errors[$key][] = $label . 'は有効な日付（YYYY-MM-DD）で入力してください。';
                }
                break;
            case 'time':
                if (is_string($value) && !preg_match('/\A([01]\d|2[0-3]):[0-5]\d\z/', $value)) {
                    $this->errors[$key][] = $label . 'は有効な時刻（HH:MM）で入力してください。';
                }
                break;
            case 'radio':
            case 'select':
                if (is_string($value) && !in_array($value, $opts, true)) {
                    $this->errors[$key][] = $label . 'の値が不正です。';
                }
                break;
            case 'checkbox':
                if (is_array($value)) {
                    foreach ($value as $v) {
                        if (!in_array($v, $opts, true)) {
                            $this->errors[$key][] = $label . 'の値が不正です。';
                            break;
                        }
                    }
                }
                break;
        }
    }

    /**
     * @param mixed $value normalize 済みの連想配列を想定
     */
    private function validateTextPairField(array $field, $value): void
    {
        $key   = $field['field_key'];
        $label = $field['label'];
        $req   = (int) ($field['required'] ?? 0) === 1;
        $maxL  = isset($field['max_length']) ? (int) $field['max_length'] : 0;
        $regexStored = $field['regex'] ?? '';
        $regexPatterns = is_string($regexStored)
            ? FormField::decodeRegexPatterns($regexStored)
            : [];
        $pairLabels = FormField::decodeTextPairLabels($field['options_json'] ?? null);
        $leftLabel = $pairLabels['left_label'] !== '' ? $pairLabels['left_label'] : '入力欄1';
        $rightLabel = $pairLabels['right_label'] !== '' ? $pairLabels['right_label'] : '入力欄2';

        if (!is_array($value)) {
            $this->addTextPairPartError($key, '_root', $label . 'の入力形式が不正です。');
            return;
        }

        $left = (string) ($value['left'] ?? '');
        $right = (string) ($value['right'] ?? '');
        $allEmpty = ($left === '' && $right === '');

        if ($req && $allEmpty) {
            $this->addTextPairPartError($key, 'left', $label . 'の' . $leftLabel . 'を入力してください。');
            $this->addTextPairPartError($key, 'right', $label . 'の' . $rightLabel . 'を入力してください。');
            return;
        }
        if (!$req && $allEmpty) {
            return;
        }
        if ($req && $left === '') {
            $this->addTextPairPartError($key, 'left', $label . 'の' . $leftLabel . 'を入力してください。');
        }
        if ($req && $right === '') {
            $this->addTextPairPartError($key, 'right', $label . 'の' . $rightLabel . 'を入力してください。');
        }

        foreach (['left' => $leftLabel, 'right' => $rightLabel] as $part => $partLabel) {
            $partValue = (string) ($value[$part] ?? '');
            if ($partValue === '') {
                continue;
            }
            if ($maxL > 0 && mb_strlen($partValue, 'UTF-8') > $maxL) {
                $this->addTextPairPartError($key, $part, $label . 'の' . $partLabel . 'は' . $maxL . '文字以内で入力してください。');
            }
            foreach ($regexPatterns as $regex) {
                if ($regex === '') {
                    continue;
                }
                if (@preg_match($regex, '') === false) {
                    $this->addTextPairPartError($key, $part, $label . 'の検証設定が不正です。');
                    break 2;
                }
                if (!preg_match($regex, $partValue)) {
                    $this->addTextPairPartError($key, $part, $label . 'の' . $partLabel . 'の形式が正しくありません。');
                    break;
                }
            }
        }
    }

    /**
     * @param mixed $value normalize 済みの連想配列を想定
     */
    private function validateAddressField(array $field, $value): void
    {
        $key   = $field['field_key'];
        $label = $field['label'];
        $req   = (int) ($field['required'] ?? 0) === 1;
        $maxL  = isset($field['max_length']) ? (int) $field['max_length'] : 0;
        $regexStored = $field['regex'] ?? '';
        $regexPatterns = is_string($regexStored)
            ? FormField::decodeRegexPatterns($regexStored)
            : [];

        if (!is_array($value)) {
            $this->addAddressPartError($key, '_root', $label . 'の入力形式が不正です。');
            return;
        }
        $postal = (string) ($value['postal'] ?? '');
        $pref   = (string) ($value['prefecture'] ?? '');
        $city   = (string) ($value['city'] ?? '');
        $number = (string) ($value['number'] ?? '');
        $build  = (string) ($value['building'] ?? '');
        $allEmpty = ($postal === '' && $pref === '' && $city === '' && $number === '' && $build === '');

        if ($req && $allEmpty) {
            $this->addAddressPartError($key, 'prefecture', $label . 'の都道府県を選択してください。');
            $this->addAddressPartError($key, 'city', $label . 'の市区町村を入力してください。');
            $this->addAddressPartError($key, 'number', $label . 'の番地を入力してください。');
            return;
        }
        // 項目全体が任意でも、何か入力があれば都道府県・市区町村・番地は必須（郵便番号・建物は任意）
        if (!$req && $allEmpty) {
            return;
        }

        $prefs = array_flip(\japanese_prefectures());

        if ($postal !== '' && !$this->isValidPostalDigits($postal)) {
            $this->addAddressPartError($key, 'postal', $label . 'の郵便番号は7桁の数字で入力してください。');
        }
        if ($pref === '') {
            $this->addAddressPartError($key, 'prefecture', $label . 'の都道府県を選択してください。');
        } elseif (!isset($prefs[$pref])) {
            $this->addAddressPartError($key, 'prefecture', $label . 'の都道府県が不正です。');
        }
        if ($city === '') {
            $this->addAddressPartError($key, 'city', $label . 'の市区町村を入力してください。');
        }
        if ($number === '') {
            $this->addAddressPartError($key, 'number', $label . 'の番地を入力してください。');
        }

        $combined = $pref . $city . $number . $build;
        if ($maxL > 0 && mb_strlen($combined, 'UTF-8') > $maxL) {
            $this->addAddressPartError($key, 'combined', $label . 'は合計' . $maxL . '文字以内で入力してください。');
        }

        foreach ($regexPatterns as $regex) {
            if ($regex === '') {
                continue;
            }
            if (@preg_match($regex, '') === false) {
                $this->addAddressPartError($key, 'combined', $label . 'の検証設定が不正です。');
                break;
            }
            if (!preg_match($regex, $combined)) {
                $this->addAddressPartError($key, 'combined', $label . 'の形式が正しくありません。');
                break;
            }
        }
    }

    /**
     * 住所フィールドのエラーを入力欄ごとに分類して格納する。
     *
     * @param string $part postal|prefecture|city|number|combined|_root
     */
    private function addAddressPartError(string $key, string $part, string $message): void
    {
        if (!isset($this->errors[$key])) {
            $this->errors[$key] = [];
        }
        if (!isset($this->errors[$key][$part])) {
            $this->errors[$key][$part] = [];
        }
        $this->errors[$key][$part][] = $message;
    }

    /**
     * @param string $part left|right|_root
     */
    private function addTextPairPartError(string $key, string $part, string $message): void
    {
        if (!isset($this->errors[$key])) {
            $this->errors[$key] = [];
        }
        if (!isset($this->errors[$key][$part])) {
            $this->errors[$key][$part] = [];
        }
        $this->errors[$key][$part][] = $message;
    }

    private function isValidPostalDigits(string $postal): bool
    {
        $d = preg_replace('/\D/', '', $postal);
        return strlen($d) === 7;
    }

    private function validateFile(array $field, $entry): void
    {
        $key   = $field['field_key'];
        $label = $field['label'];
        $req   = (int) ($field['required'] ?? 0) === 1;

        if (!$entry || !isset($entry['error']) || $entry['error'] === UPLOAD_ERR_NO_FILE) {
            if ($req) {
                $this->errors[$key][] = $label . 'は必須項目です。';
            }
            return;
        }

        if ($entry['error'] !== UPLOAD_ERR_OK) {
            $this->errors[$key][] = $label . 'のアップロードに失敗しました（コード:' . (int) $entry['error'] . '）';
            return;
        }

        $maxSize = (int) config('upload.max_size', 5 * 1024 * 1024);
        if (($entry['size'] ?? 0) > $maxSize) {
            $this->errors[$key][] = $label . 'のファイルサイズが大きすぎます（最大' . self::formatBytes($maxSize) . '）。';
            return;
        }

        $allowed = array_map('strtolower', (array) config('upload.allowed_extensions', []));
        $ext = strtolower(pathinfo($entry['name'] ?? '', PATHINFO_EXTENSION));
        if ($ext === '' || !in_array($ext, $allowed, true)) {
            $this->errors[$key][] = $label . 'のファイル形式が許可されていません。';
            return;
        }

        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $entry['tmp_name']);
            finfo_close($finfo);
            if ($mime === false) {
                $this->errors[$key][] = $label . 'のファイルが読み取れません。';
            }
        }
    }

    private function isValidDate(string $value): bool
    {
        $d = \DateTime::createFromFormat('Y-m-d', $value);
        return $d && $d->format('Y-m-d') === $value;
    }

    public static function decodeOptions($json): array
    {
        if (empty($json)) {
            return [];
        }
        $arr = json_decode($json, true);
        if (!is_array($arr) || array_keys($arr) !== range(0, count($arr) - 1)) {
            return [];
        }
        return array_values(array_filter(array_map('strval', $arr), fn($v) => $v !== ''));
    }

    public static function formatBytes(int $bytes): string
    {
        if ($bytes >= 1024 * 1024) return round($bytes / 1024 / 1024, 1) . 'MB';
        if ($bytes >= 1024)        return round($bytes / 1024, 1) . 'KB';
        return $bytes . 'B';
    }
}
