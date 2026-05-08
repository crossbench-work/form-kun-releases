<?php
namespace Core;

class Captcha
{
    private const SESSION_KEY = '_captcha_answer';
    private const SESSION_QUESTION = '_captcha_question';

    public static function generate(): string
    {
        $a = random_int(1, 9);
        $b = random_int(1, 9);
        $op = random_int(0, 1) === 0 ? '+' : '-';
        if ($op === '-' && $b > $a) {
            [$a, $b] = [$b, $a];
        }
        $answer = $op === '+' ? $a + $b : $a - $b;
        $question = sprintf('%d %s %d', $a, $op, $b);

        $_SESSION[self::SESSION_KEY] = (string) $answer;
        $_SESSION[self::SESSION_QUESTION] = $question;
        return $question;
    }

    public static function question(): string
    {
        if (empty($_SESSION[self::SESSION_QUESTION])) {
            return self::generate();
        }
        return $_SESSION[self::SESSION_QUESTION];
    }

    public static function check(?string $userInput): bool
    {
        $expected = $_SESSION[self::SESSION_KEY] ?? null;
        if ($expected === null || $userInput === null || trim($userInput) === '') {
            return false;
        }
        $userInput = self::normalizeNumber($userInput);
        // 一度きりのチェック
        unset($_SESSION[self::SESSION_KEY], $_SESSION[self::SESSION_QUESTION]);
        return $userInput === (string) $expected;
    }

    /**
     * 全角数字や符号を半角に正規化。
     */
    private static function normalizeNumber(string $s): string
    {
        $s = trim($s);
        if (function_exists('mb_convert_kana')) {
            $s = mb_convert_kana($s, 'n', 'UTF-8');
        }
        return $s;
    }

    public static function clear(): void
    {
        unset($_SESSION[self::SESSION_KEY], $_SESSION[self::SESSION_QUESTION]);
    }
}
