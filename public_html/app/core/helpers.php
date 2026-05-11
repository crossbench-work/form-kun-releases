<?php
// app/core/helpers.php

if (!function_exists('e')) {
    /**
     * HTML エスケープ。
     */
    function e($value): string
    {
        if ($value === null) {
            return '';
        }
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('config')) {
    /**
     * ドット区切りで設定を取得。
     */
    function config(string $key, $default = null)
    {
        $cfg = FORMKUN_CONFIG;
        foreach (explode('.', $key) as $k) {
            if (is_array($cfg) && array_key_exists($k, $cfg)) {
                $cfg = $cfg[$k];
            } else {
                return $default;
            }
        }
        return $cfg;
    }
}

if (!function_exists('base_path')) {
    /**
     * サブディレクトリ運用に対応したベースパス + 任意のパス。
     */
    function base_path(string $path = ''): string
    {
        $base = rtrim((string) config('site.base_path', ''), '/');
        if ($path !== '' && $path[0] !== '/') {
            $path = '/' . $path;
        }
        return $base . $path;
    }
}

if (!function_exists('url')) {
    /**
     * 絶対URLが必要なケース用。base_url が設定されていれば使う。
     */
    function url(string $path = ''): string
    {
        $baseUrl = rtrim((string) config('site.base_url', ''), '/');
        if ($baseUrl !== '') {
            return $baseUrl . base_path($path);
        }
        return base_path($path);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        header('Location: ' . base_path($path));
        exit;
    }
}

if (!function_exists('flash')) {
    /**
     * フラッシュメッセージ取得 or 設定。
     */
    function flash(?string $type = null, ?string $message = null)
    {
        if ($type === null) {
            $messages = $_SESSION['_flash'] ?? [];
            unset($_SESSION['_flash']);
            return $messages;
        }
        $_SESSION['_flash'][] = ['type' => $type, 'message' => $message];
    }
}

if (!function_exists('old')) {
    /**
     * 直前の入力値（バリデーションエラー時の再表示用）。
     */
    function old(string $key, $default = '')
    {
        $old = $_SESSION['_old'] ?? [];
        return $old[$key] ?? $default;
    }
}

if (!function_exists('client_ip')) {
    function client_ip(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        return substr($ip, 0, 45);
    }
}

if (!function_exists('sanitize_rich_text_html')) {
    /**
     * 軽量 WYSIWYG 用の許可タグだけを残して HTML を整形する。
     */
    function sanitize_rich_text_html($html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }
        if (preg_match('/<\s*\/?[a-zA-Z][^>]*>/', $html) !== 1) {
            return $html;
        }

        $allowedTags = [
            'a' => ['href'],
            'b' => [],
            'br' => [],
            'div' => [],
            'em' => [],
            'i' => [],
            'li' => [],
            'ol' => [],
            'p' => [],
            'strong' => [],
            'u' => [],
            'ul' => [],
        ];
        $dropTags = ['script', 'style', 'iframe', 'object', 'embed'];

        $previous = libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $wrappedHtml = '<div id="__rich_text_root__">' . $html . '</div>';
        $dom->loadHTML(
            mb_encode_numericentity($wrappedHtml, [0x80, 0x10FFFF, 0, 0xFFFFFF], 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new \DOMXPath($dom);
        $root = $xpath->query('//*[@id="__rich_text_root__"]')->item(0);
        if (!$root instanceof \DOMElement) {
            return strip_tags($html);
        }

        $sanitizeUrl = static function (string $url): string {
            $url = trim($url);
            if ($url === '') {
                return '';
            }
            if (preg_match('/^(https?:|mailto:|tel:|\/|#)/i', $url) === 1) {
                return $url;
            }
            return '';
        };

        $sanitizeNode = static function (\DOMNode $node) use (&$sanitizeNode, $allowedTags, $dropTags, $sanitizeUrl): void {
            if ($node instanceof \DOMText) {
                return;
            }

            if (!($node instanceof \DOMElement)) {
                if ($node->parentNode) {
                    $node->parentNode->removeChild($node);
                }
                return;
            }

            $tagName = strtolower($node->tagName);
            if (in_array($tagName, $dropTags, true)) {
                if ($node->parentNode) {
                    $node->parentNode->removeChild($node);
                }
                return;
            }

            if (!array_key_exists($tagName, $allowedTags)) {
                if ($node->parentNode) {
                    while ($node->firstChild) {
                        $node->parentNode->insertBefore($node->firstChild, $node);
                    }
                    $node->parentNode->removeChild($node);
                }
                return;
            }

            $allowedAttrs = $allowedTags[$tagName];
            if ($node->hasAttributes()) {
                $removeAttrs = [];
                foreach ($node->attributes as $attr) {
                    $attrName = strtolower($attr->nodeName);
                    if (!in_array($attrName, $allowedAttrs, true)) {
                        $removeAttrs[] = $attrName;
                    }
                }
                foreach ($removeAttrs as $attrName) {
                    $node->removeAttribute($attrName);
                }
            }

            if ($tagName === 'a' && $node->hasAttribute('href')) {
                $safeHref = $sanitizeUrl((string) $node->getAttribute('href'));
                if ($safeHref === '') {
                    $node->removeAttribute('href');
                } else {
                    $node->setAttribute('href', $safeHref);
                }
            }

            for ($child = $node->firstChild; $child !== null; $child = $next) {
                $next = $child->nextSibling;
                $sanitizeNode($child);
            }
        };

        for ($child = $root->firstChild; $child !== null; $child = $next) {
            $next = $child->nextSibling;
            $sanitizeNode($child);
        }

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        return trim($out);
    }
}

if (!function_exists('render_rich_text_html')) {
    /**
     * 旧プレーンテキストと WYSIWYG 保存 HTML の両方を安全に表示する。
     */
    function render_rich_text_html($html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }
        if (preg_match('/<\s*\/?[a-zA-Z][^>]*>/', $html) !== 1) {
            return nl2br(e($html));
        }

        return sanitize_rich_text_html($html);
    }
}

if (!function_exists('address_errors_normalize')) {
    /**
     * 住所フィールドのエラーを表示用に整形（ネスト or 従来の文字列リスト）。
     *
     * @param mixed $err
     * @return array{_root:string[],postal:string[],prefecture:string[],city:string[],number:string[],combined:string[]}
     */
    function address_errors_normalize($err): array
    {
        $defaults = [
            '_root'       => [],
            'postal'      => [],
            'prefecture'  => [],
            'city'        => [],
            'number'      => [],
            'combined'    => [],
        ];
        if (!is_array($err) || $err === []) {
            return $defaults;
        }
        $first = array_key_first($err);
        $looksNested = isset($err['prefecture']) || isset($err['postal']) || isset($err['city'])
            || isset($err['number']) || isset($err['combined']) || isset($err['_root']);
        if (!$looksNested && $first !== null) {
            foreach ($err as $m) {
                if (is_string($m)) {
                    $defaults['combined'][] = $m;
                }
            }
            return $defaults;
        }
        foreach (['_root', 'postal', 'prefecture', 'city', 'number', 'combined'] as $k) {
            if (!empty($err[$k]) && is_array($err[$k])) {
                foreach ($err[$k] as $m) {
                    if (is_string($m)) {
                        $defaults[$k][] = $m;
                    }
                }
            }
        }
        return $defaults;
    }
}

if (!function_exists('address_errors_any')) {
    /** @param array{_root?:string[],postal?:string[],prefecture?:string[],city?:string[],number?:string[],combined?:string[]} $parts */
    function address_errors_any(array $parts): bool
    {
        foreach ($parts as $msgs) {
            if (!empty($msgs)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('text_pair_errors_normalize')) {
    /**
     * @param mixed $err
     * @return array{_root:string[],left:string[],right:string[]}
     */
    function text_pair_errors_normalize($err): array
    {
        $defaults = [
            '_root' => [],
            'left' => [],
            'right' => [],
        ];
        if (!is_array($err) || $err === []) {
            return $defaults;
        }
        $looksNested = isset($err['left']) || isset($err['right']) || isset($err['_root']);
        if (!$looksNested) {
            foreach ($err as $m) {
                if (is_string($m)) {
                    $defaults['_root'][] = $m;
                }
            }
            return $defaults;
        }
        foreach (['_root', 'left', 'right'] as $k) {
            if (!empty($err[$k]) && is_array($err[$k])) {
                foreach ($err[$k] as $m) {
                    if (is_string($m)) {
                        $defaults[$k][] = $m;
                    }
                }
            }
        }
        return $defaults;
    }
}

if (!function_exists('japanese_prefectures')) {
    /**
     * @return string[] 都道府県名（47）
     */
    function japanese_prefectures(): array
    {
        return [
            '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
            '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
            '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県',
            '岐阜県', '静岡県', '愛知県', '三重県',
            '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県',
            '鳥取県', '島根県', '岡山県', '広島県', '山口県',
            '徳島県', '香川県', '愛媛県', '高知県',
            '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県',
        ];
    }
}

if (!function_exists('address_split_legacy_line1')) {
    /**
     * 旧フォーマット（line1 に都道府県+市区町村が連結）から都道府県と市区町村へ分割。
     *
     * @return array{0:string,1:string} [都道府県, 市区町村]
     */
    function address_split_legacy_line1(string $line1): array
    {
        foreach (japanese_prefectures() as $pref) {
            if ($pref !== '' && mb_strpos($line1, $pref, 0, 'UTF-8') === 0) {
                $rest = trim(mb_substr($line1, mb_strlen($pref, 'UTF-8'), null, 'UTF-8'));
                return [$pref, $rest];
            }
        }
        return ['', $line1];
    }
}

if (!function_exists('format_address_parts')) {
    /**
     * 旧フォーマット（postal + line1 + line2）を表示用テキストに整形。
     *
     * @return string 改行区切り（空なら空文字）
     */
    function format_address_parts(string $postal, string $line1, string $line2): string
    {
        $lines = [];
        if ($postal !== '') {
            $d = preg_replace('/\D/', '', $postal);
            $lines[] = (strlen($d) === 7)
                ? ('〒' . substr($d, 0, 3) . '-' . substr($d, 3))
                : ('〒' . $postal);
        }
        if ($line1 !== '') {
            $lines[] = $line1;
        }
        if ($line2 !== '') {
            $lines[] = $line2;
        }
        return implode("\n", $lines);
    }
}

if (!function_exists('format_address_parts_v2')) {
    /**
     * 中間フォーマット（番地・建物が1フィールド street にまとまっている場合）。
     */
    function format_address_parts_v2(string $postal, string $prefecture, string $city, string $street): string
    {
        $lines = [];
        if ($postal !== '') {
            $d = preg_replace('/\D/', '', $postal);
            $lines[] = (strlen($d) === 7)
                ? ('〒' . substr($d, 0, 3) . '-' . substr($d, 3))
                : ('〒' . $postal);
        }
        if ($prefecture !== '') {
            $lines[] = $prefecture;
        }
        if ($city !== '') {
            $lines[] = $city;
        }
        if ($street !== '') {
            $lines[] = $street;
        }
        return implode("\n", $lines);
    }
}

if (!function_exists('format_address_parts_v3')) {
    /**
     * 現行フォーマット（番地・建物を分割）。
     */
    function format_address_parts_v3(string $postal, string $prefecture, string $city, string $number, string $building): string
    {
        $lines = [];
        if ($postal !== '') {
            $d = preg_replace('/\D/', '', $postal);
            $lines[] = (strlen($d) === 7)
                ? ('〒' . substr($d, 0, 3) . '-' . substr($d, 3))
                : ('〒' . $postal);
        }
        if ($prefecture !== '') {
            $lines[] = $prefecture;
        }
        if ($city !== '') {
            $lines[] = $city;
        }
        if ($number !== '') {
            $lines[] = $number;
        }
        if ($building !== '') {
            $lines[] = $building;
        }
        return implode("\n", $lines);
    }
}

if (!function_exists('format_address_value_display')) {
    /**
     * 送信値・DB保存値（JSON 文字列含む）を確認画面・メール用の表示文に変換。
     *
     * @param array|string $value
     */
    function format_address_value_display($value): string
    {
        if (is_array($value)) {
            return format_address_array_for_display($value);
        }
        $s = (string) $value;
        if ($s === '') {
            return '';
        }
        $decoded = json_decode($s, true);
        if (is_array($decoded) && (array_key_exists('postal', $decoded) || array_key_exists('line1', $decoded)
            || array_key_exists('prefecture', $decoded) || array_key_exists('city', $decoded)
            || array_key_exists('number', $decoded) || array_key_exists('building', $decoded))) {
            return format_address_array_for_display($decoded);
        }
        return $s;
    }
}

if (!function_exists('format_text_pair_value_display')) {
    /**
     * @param array|string $value
     * @param array{left_label?:string,right_label?:string}|null $labels
     */
    function format_text_pair_value_display($value, ?array $labels = null): string
    {
        $labels = $labels ?? [];
        $leftLabel = trim((string) ($labels['left_label'] ?? ''));
        $rightLabel = trim((string) ($labels['right_label'] ?? ''));
        $left = '';
        $right = '';

        if (is_array($value)) {
            $left = trim((string) ($value['left'] ?? ''));
            $right = trim((string) ($value['right'] ?? ''));
        } else {
            $s = trim((string) $value);
            if ($s === '') {
                return '';
            }
            $decoded = json_decode($s, true);
            if (is_array($decoded) && (isset($decoded['left']) || isset($decoded['right']))) {
                $left = trim((string) ($decoded['left'] ?? ''));
                $right = trim((string) ($decoded['right'] ?? ''));
            } else {
                $parts = preg_split('/\r\n|\r|\n/', $s) ?: [];
                $left = trim((string) ($parts[0] ?? ''));
                $right = trim((string) ($parts[1] ?? ''));
            }
        }

        $lines = [];
        if ($left !== '') {
            $lines[] = ($leftLabel !== '' ? $leftLabel . ': ' : '') . $left;
        }
        if ($right !== '') {
            $lines[] = ($rightLabel !== '' ? $rightLabel . ': ' : '') . $right;
        }
        return implode("\n", $lines);
    }
}

if (!function_exists('format_address_array_for_display')) {
    /**
     * @param array<string, mixed> $a
     */
    function format_address_array_for_display(array $a): string
    {
        $postal = (string) ($a['postal'] ?? '');
        $hasLegacyOnly = (array_key_exists('line1', $a) || array_key_exists('line2', $a))
            && !array_key_exists('prefecture', $a) && !array_key_exists('city', $a)
            && !array_key_exists('street', $a) && !array_key_exists('number', $a) && !array_key_exists('building', $a);
        if ($hasLegacyOnly) {
            return format_address_parts($postal, (string) ($a['line1'] ?? ''), (string) ($a['line2'] ?? ''));
        }

        $pref = (string) ($a['prefecture'] ?? '');
        $city = (string) ($a['city'] ?? '');
        $num = (string) ($a['number'] ?? '');
        $bld = (string) ($a['building'] ?? '');
        $street = (string) ($a['street'] ?? '');

        if (array_key_exists('number', $a) || array_key_exists('building', $a)) {
            return format_address_parts_v3($postal, $pref, $city, $num, $bld);
        }

        return format_address_parts_v2($postal, $pref, $city, $street);
    }
}
