<?php

require_once __DIR__ . '/../controllers/TaskController.php';

class Router {
    private $routes = [];

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path if exists
        $path = str_replace('/index.php', '', $path);
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $path)) {
                $params = $this->extractParams($route['path'], $path);
                call_user_func_array($route['handler'], $params);
                return;
            }
        }
        
        // No route found
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Route not found'
        ], JSON_PRETTY_PRINT);
    }

    private function matchPath($routePath, $requestPath) {
        $routeParts = explode('/', trim($routePath, '/'));
        $requestParts = explode('/', trim($requestPath, '/'));
        
        if (count($routeParts) !== count($requestParts)) {
            return false;
        }
        
        for ($i = 0; $i < count($routeParts); $i++) {
            if (strpos($routeParts[$i], '{') === 0 && strpos($routeParts[$i], '}') === strlen($routeParts[$i]) - 1) {
                continue; // Parameter placeholder
            }
            
            if ($routeParts[$i] !== $requestParts[$i]) {
                return false;
            }
        }
        
        return true;
    }

    private function extractParams($routePath, $requestPath) {
        $routeParts = explode('/', trim($routePath, '/'));
        $requestParts = explode('/', trim($requestPath, '/'));
        $params = [];
        
        for ($i = 0; $i < count($routeParts); $i++) {
            if (strpos($routeParts[$i], '{') === 0 && strpos($routeParts[$i], '}') === strlen($routeParts[$i]) - 1) {
                $paramName = trim($routeParts[$i], '{}');
                $params[] = $requestParts[$i];
            }
        }
        
        return $params;
    }
} 