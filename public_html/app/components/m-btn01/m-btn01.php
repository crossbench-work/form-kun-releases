<?php
/**
 * 管理画面ボタンラッパー m-btn01 のマークアップヘルパー。
 * スタイルは同ディレクトリの _m-btn01.scss（_admin-app.scss から @use）。
 */
if (!function_exists('admin_m_btn01_open')) {
    /**
     * @param string|null $dataPosition 例: center のとき `data-position="center"` を付与
     */
    function admin_m_btn01_open(?string $dataPosition = null): void
    {
        echo '<div data-module="m-btn01"';
        if ($dataPosition === 'center') {
            echo ' data-position="center"';
        }
        echo '>';
    }

    function admin_m_btn01_close(): void
    {
        echo '</div>';
    }

    /**
     * HTML 属性の断片を組み立てる。値は e() でエスケープする（true は論理属性としてキーのみ出力）。
     *
     * @param array<string, string|int|float|bool|null> $attrs
     */
    function admin_m_btn01_attrs(array $attrs): string
    {
        $parts = [];
        foreach ($attrs as $name => $value) {
            if ($value === null || $value === false) {
                continue;
            }
            $nameStr = (string) $name;
            if ($value === true) {
                $parts[] = e($nameStr);
                continue;
            }
            $parts[] = e($nameStr) . '="' . e((string) $value) . '"';
        }

        return implode(' ', $parts);
    }

    /**
     * @param array<string, string|int|float|bool|null> $attrs
     * @return array<string, string|int|float|bool|null>
     */
    function admin_m_btn01_normalize_attrs(array $attrs, bool $isLink): array
    {
        $classRaw = trim((string) ($attrs['class'] ?? ''));
        unset($attrs['class']);

        $classes = ['btn'];
        if ($classRaw !== '') {
            foreach (preg_split('/\s+/', $classRaw) as $token) {
                $token = trim($token);
                if ($token !== '' && $token !== 'btn' && !in_array($token, $classes, true)) {
                    $classes[] = $token;
                }
            }
        }
        $attrs['class'] = implode(' ', $classes);

        if ($isLink) {
            return $attrs;
        }

        if (!isset($attrs['type'])) {
            $attrs['type'] = 'button';
        }

        return $attrs;
    }

    /**
     * m-btn01 ラッパー内の &lt;button&gt; を出力する（open/close は呼び出し側）。
     *
     * @param array<string, string|int|float|bool|null> $attrs class は `btn` とマージ（is-primary 等）
     */
    function admin_m_btn01_button(string $label, array $attrs = []): void
    {
        $attrs = admin_m_btn01_normalize_attrs($attrs, false);
        $s = admin_m_btn01_attrs($attrs);
        echo '<button' . ($s !== '' ? ' ' . $s : '') . '>' . e($label) . '</button>';
    }

    /**
     * m-btn01 ラッパー内の &lt;a class="btn"&gt; を出力する（open/close は呼び出し側）。
     *
     * @param array<string, string|int|float|bool|null> $attrs href 以外の属性（class は `btn` とマージ）
     */
    function admin_m_btn01_link(string $label, string $href, array $attrs = []): void
    {
        $attrs['href'] = $href;
        $attrs = admin_m_btn01_normalize_attrs($attrs, true);
        $s = admin_m_btn01_attrs($attrs);
        echo '<a' . ($s !== '' ? ' ' . $s : '') . '>' . e($label) . '</a>';
    }

    /**
     * フォーム編集タブのプレビュー用 submit（formaction 等を付与）。$formactionPath は base_path 済み URL。
     */
    function admin_m_btn01_preview_submit(string $formactionPath, string $label = 'プレビュー'): void
    {
        admin_m_btn01_button($label, [
            'type' => 'submit',
            'formaction' => $formactionPath,
            'formmethod' => 'post',
            'formtarget' => '_blank',
            'formnovalidate' => true,
        ]);
    }
}
