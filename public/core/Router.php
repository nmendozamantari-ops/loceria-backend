<?php
class Router {
    private $routes = [];

    public function get($path, $action, $middleware = null) {
        $this->addRoute('GET', $path, $action, $middleware);
    }

    public function post($path, $action, $middleware = null) {
        $this->addRoute('POST', $path, $action, $middleware);
    }

    public function put($path, $action, $middleware = null) {
        $this->addRoute('PUT', $path, $action, $middleware);
    }

    public function delete($path, $action, $middleware = null) {
        $this->addRoute('DELETE', $path, $action, $middleware);
    }

    private function addRoute($method, $path, $action, $middleware = null) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public function run() {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // 🔥 Manejo del preflight OPTIONS (CORS)
        if ($requestMethod === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        // Quitar el prefijo "/public" si existe
        if (str_starts_with($requestUri, '/public')) {
            $requestUri = substr($requestUri, strlen('/public'));
        }

        // Buscar la ruta que coincida
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['path'] === $requestUri) {
                return $this->dispatch($route['action'], $route['middleware']);
            }
        }

        // Si no se encuentra la ruta
        http_response_code(404);
        echo json_encode([
            'error' => 'Ruta no encontrada',
            'path' => $requestUri,
            'method' => $requestMethod
        ]);
    }

    private function dispatch($action, $middleware = null) {
        list($controllerName, $methodName) = explode('@', $action);
        $controllerFile = __DIR__ . '/../app/Controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            throw new Exception("Controlador $controllerName no encontrado");
        }

        require_once $controllerFile;

        // Middleware
        $authPayload = null;
        if ($middleware) {
            $authPayload = $this->executeMiddleware($middleware);
        }

        // Instanciar el controlador
        $controller = new $controllerName();

        // Leer JSON enviado
        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        // Inyectar usuario autenticado
        if ($authPayload) {
            $data['auth_user'] = $authPayload;
        }

        // Ejecutar el método del controlador
        return $controller->$methodName($data);
    }

    private function executeMiddleware($middleware) {
        if (is_string($middleware)) {
            $middlewareFile = __DIR__ . '/../app/Middlewares/' . $middleware . '.php';
            if (!file_exists($middlewareFile)) {
                throw new Exception("Middleware $middleware no encontrado");
            }
            require_once $middlewareFile;
            return $middleware::authenticate();
        } 
        elseif (is_array($middleware)) {
            $middlewareClass = $middleware['middleware'];
            $middlewareFile = __DIR__ . '/../app/Middlewares/' . $middlewareClass . '.php';

            if (!file_exists($middlewareFile)) {
                throw new Exception("Middleware $middlewareClass no encontrado");
            }

            require_once $middlewareFile;

            if (isset($middleware['roles'])) {
                return $middlewareClass::requireRole($middleware['roles']);
            } else {
                return $middlewareClass::authenticate();
            }
        }

        return null;
    }
}
