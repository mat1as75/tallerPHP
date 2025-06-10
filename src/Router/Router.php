<?php
namespace App\Router;

class Router
{
    private $uri;
    private $method;
    private $routes = [];

    public function __construct($uri, $method)
    {
        $path = parse_url($uri, PHP_URL_PATH);

        // Request desde Deployment
        if (str_contains($path, 'tallerPHP'))
            $path = str_replace('/tallerPHP', '', $path);

        $clean = str_replace('/index.php', '', $path);
        $parts = explode('/', $clean);
        $beforePattern = '/' . $parts[1] . '/' . $parts[2];
        $pattern = str_replace($beforePattern, '', $clean);
        //echo "Ruta: " . $path . "\n";
        //echo "Ruta limpia: " . $clean . "\n";
        //echo "Antes del patrón: " . $beforePattern . "\n";
        //echo "Pattern: " . $pattern . "\n";

        $this->uri = $pattern;
        $this->method = $method;
    }

    public function add($method, $pattern, $callback)
    {
        $this->routes[] = compact('method', 'pattern', 'callback');
    }

    public function dispatch()
    {
        foreach ($this->routes as $route) {
            if ($this->method !== $route['method']) {
                continue;
            }

            $regex = preg_replace('#\{[a-zA-Z_]+\}#', '([a-zA-Z0-9_-]+)', $route['pattern']);
            $regex = "#^" . $regex . "$#";

            if (preg_match($regex, $this->uri, $matches)) {
                array_shift($matches);
                call_user_func_array($route['callback'], $matches);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(["mensaje" => "Ruta no encontrada"]);
    }
}
