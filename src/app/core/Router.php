<?php
namespace Core;

class Router
{
    /** @var array<int, array{method:string, pattern:string, regex:string, params:array, handler:callable}> */
    private array $routes = [];
    private string $prefix;

    public function __construct(string $prefix = '')
    {
        $this->prefix = rtrim($prefix, '/');
    }

    public function get(string $path, callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, callable $handler): void
    {
        $params = [];
        $regex = preg_replace_callback('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', function ($m) use (&$params) {
            $params[] = $m[1];
            return '([^/]+)';
        }, $path);
        $regex = '#^' . $regex . '$#';

        $this->routes[] = [
            'method'  => $method,
            'pattern' => $path,
            'regex'   => $regex,
            'params'  => $params,
            'handler' => $handler,
        ];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ($method === 'HEAD') {
            $method = 'GET';
        }

        $path = $this->resolvePath();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($route['regex'], $path, $matches)) {
                array_shift($matches);
                $params = [];
                foreach ($route['params'] as $i => $name) {
                    $params[$name] = urldecode($matches[$i] ?? '');
                }
                ($route['handler'])($params);
                return;
            }
        }

        $this->notFound();
    }

    private function resolvePath(): string
    {
        // ?path=... フォールバック対応（mod_rewrite が無い環境）
        if (isset($_GET['path']) && $_GET['path'] !== '') {
            $path = '/' . ltrim((string) $_GET['path'], '/');
        } else {
            $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
            // base_path を除去
            $base = rtrim((string) config('site.base_path', ''), '/');
            if ($base !== '' && strpos($uri, $base) === 0) {
                $uri = substr($uri, strlen($base));
            }
            $path = '/' . ltrim($uri, '/');
        }

        // admin/ 領域では prefix を取り除く
        if ($this->prefix !== '' && strpos($path, $this->prefix) === 0) {
            $path = substr($path, strlen($this->prefix));
            if ($path === '' || $path === false) {
                $path = '/';
            }
        }

        // 末尾スラッシュを正規化（ルート以外は除去）
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }

        return $path;
    }

    private function notFound(): void
    {
        http_response_code(404);
        header('Content-Type: text/html; charset=UTF-8');
        echo '<!doctype html><meta charset="utf-8"><title>404 Not Found</title>';
        echo '<h1>404 Not Found</h1>';
        echo '<p>お探しのページは見つかりませんでした。</p>';
    }
}
