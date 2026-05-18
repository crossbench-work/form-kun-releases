<?php
namespace Core;

class View
{
    private string $viewsDir;

    public function __construct()
    {
        $this->viewsDir = APP_DIR . '/views';
    }

    /**
     * テンプレートをレンダリングしてレスポンスとして送信。
     */
    public function render(string $template, array $data = [], ?string $layout = null): void
    {
        echo $this->renderToString($template, $data, $layout);
    }

    public function renderToString(string $template, array $data = [], ?string $layout = null): string
    {
        // レイアウトより先にテンプレートが include されるため、レイアウト内の require では遅い。
        if ($layout === 'admin' || $layout === 'admin_minimal') {
            require_once $this->viewsDir . '/layouts/admin_components.php';
        }
        $content = $this->renderRaw($template, $data);
        if ($layout === null) {
            return $content;
        }
        $data['content'] = $content;
        return $this->renderRaw('layouts/' . $layout, $data);
    }

    public function renderRaw(string $template, array $data = []): string
    {
        $file = $this->viewsDir . '/' . ltrim($template, '/') . '.php';
        if (!is_file($file)) {
            throw new \RuntimeException('View not found: ' . $template);
        }
        extract($data, EXTR_SKIP);
        ob_start();
        include $file;
        return (string) ob_get_clean();
    }
}
