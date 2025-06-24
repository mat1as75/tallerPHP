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

        if (str_contains($path, 'tallerPHP')) {
            $this->uri = str_replace('/php/tallerPHP/public/index.php', '', $path);
        } elseif (str_contains($path, 'equipo2')) {
            $this->uri = str_replace('/equipo2/backend/public/index.php', '', $path);
        } else {
            // fallback si no coincide nada
            $this->uri = $path;
        }
        // echo "PATH: " . $path . "\n";
        // echo "URI: " . $this->uri . "\n";
        // $clean = str_replace('/index.php', '', $path);
        // $parts = explode('/', $clean);
        // $beforePattern = '/' . $parts[1] . '/' . $parts[2];
        // $pattern = str_replace($beforePattern, '', $clean);
        //echo "Ruta: " . $path . "\n";
        //echo "Ruta limpia: " . $clean . "\n";
        //echo "Antes del patrón: " . $beforePattern . "\n";
        //echo "Pattern: " . $pattern . "\n";


        //$this->uri = $pattern;
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
