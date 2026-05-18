<?php
namespace Core;

/**
 * フォーム項目の field_key（UUID 自動生成・手動キー）の検証。
 */
class FieldKey
{
    /**
     * 標準形式 UUID v4（36文字、ハイフン区切り）。
     */
    public static function generateUuidV4(): string
    {
        $b = random_bytes(16);
        $b[6] = chr((ord($b[6]) & 0x0f) | 0x40);
        $b[8] = chr((ord($b[8]) & 0x3f) | 0x80);
        $h = bin2hex($b);
        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($h, 0, 8),
            substr($h, 8, 4),
            substr($h, 12, 4),
            substr($h, 16, 4),
            substr($h, 20, 12)
        );
    }

    public static function isUuidFormat(string $key): bool
    {
        return (bool) preg_match(
            '/\A[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\z/i',
            $key
        );
    }

    /** 従来どおりの識別子（英字始まり・英数字・アンダースコア）。 */
    public static function isLegacyIdentifier(string $key): bool
    {
        return (bool) preg_match('/\A[a-zA-Z][a-zA-Z0-9_]{0,63}\z/', $key);
    }

    public static function isValid(string $key): bool
    {
        return self::isUuidFormat($key) || self::isLegacyIdentifier($key);
    }
}
