<?php
/**
 * Router - Flexible Routing System dengan Base Path Detection
 * Support untuk deployment di berbagai environment
 */

class Router {
    private $routes = [];
    private $notFoundCallback;
    private $basePath;
    
    public function __construct() {
        // Auto-detect base path
        $this->basePath = BASE_PATH ?? '';
    }
    
    /**
     * Register GET route
     */
    public function get($path, $callback) {
        $this->addRoute('GET', $path, $callback);
    }
    
    /**
     * Register POST route
     */
    public function post($path, $callback) {
        $this->addRoute('POST', $path, $callback);
    }
    
    /**
     * Register PUT route
     */
    public function put($path, $callback) {
        $this->addRoute('PUT', $path, $callback);
    }
    
    /**
     * Register DELETE route
     */
    public function delete($path, $callback) {
        $this->addRoute('DELETE', $path, $callback);
    }
    
    /**
     * Register route untuk semua methods
     */
    public function any($path, $callback) {
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
        foreach ($methods as $method) {
            $this->addRoute($method, $path, $callback);
        }
    }
    
    /**
     * Add route ke registry
     */
    private function addRoute($method, $path, $callback) {
        // Normalize path
        $path = $this->normalizePath($path);
        
        // Convert path ke regex pattern
        $pattern = $this->convertToRegex($path);
        
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'callback' => $callback
        ];
    }
    
    /**
     * Normalize path (remove trailing slash, add leading slash)
     */
    private function normalizePath($path) {
        $path = '/' . trim($path, '/');
        return $path === '/' ? '/' : $path;
    }
    
    /**
     * Convert path dengan parameter ke regex
     * Contoh: /user/{id} => /user/([^/]+)
     */
    private function convertToRegex($path) {
        // Replace {param} dengan regex capture group
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $path);
        
        // Escape forward slashes
        $pattern = str_replace('/', '\/', $pattern);
        
        return '/^' . $pattern . '$/';
    }
    
    /**
     * Set 404 Not Found callback
     */
    public function notFound($callback) {
        $this->notFoundCallback = $callback;
    }
    
    /**
     * Run router - match request dengan routes
     */
    public function run() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        $requestUri = strtok($requestUri, '?');
        
        // Remove base path dari URI
        if (!empty($this->basePath)) {
            $requestUri = substr($requestUri, strlen($this->basePath));
        }
        
        // Normalize URI
        $requestUri = $this->normalizePath($requestUri);
        
        // Cari matching route
        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }
            
            // Check if pattern matches
            if (preg_match($route['pattern'], $requestUri, $matches)) {
                // Remove full match dari array
                array_shift($matches);
                
                // Execute callback dengan parameters
                $this->executeCallback($route['callback'], $matches);
                return;
            }
        }
        
        // Jika tidak ada route yang match, panggil 404
        $this->execute404();
    }
    
    /**
     * Execute callback dengan parameters
     */
    private function executeCallback($callback, $params = []) {
        if (is_callable($callback)) {
            call_user_func_array($callback, $params);
        } elseif (is_string($callback)) {
            // Format: "ControllerName@method"
            if (strpos($callback, '@') !== false) {
                list($controller, $method) = explode('@', $callback);
                $this->executeController($controller, $method, $params);
            }
        }
    }
    
    /**
     * Execute controller method
     */
    private function executeController($controller, $method, $params = []) {
        $controllerFile = __DIR__ . '/../controllers/' . $controller . '.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            if (class_exists($controller)) {
                $controllerInstance = new $controller();
                
                if (method_exists($controllerInstance, $method)) {
                    call_user_func_array([$controllerInstance, $method], $params);
                    return;
                }
            }
        }
        
        $this->execute404();
    }
    
    /**
     * Execute 404 callback
     */
    private function execute404() {
        http_response_code(404);
        
        if ($this->notFoundCallback && is_callable($this->notFoundCallback)) {
            call_user_func($this->notFoundCallback);
        } else {
            // Default 404 response
            if ($this->isApiRequest()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Endpoint tidak ditemukan'
                ]);
            } else {
                echo '<h1>404 - Halaman Tidak Ditemukan</h1>';
            }
        }
    }
    
    /**
     * Check if request is API request
     */
    private function isApiRequest() {
        $requestUri = $_SERVER['REQUEST_URI'];
        return strpos($requestUri, '/api/') !== false;
    }
    
    /**
     * Redirect helper
     */
    public static function redirect($path, $statusCode = 302) {
        $baseUrl = BASE_URL ?? '/';
        $url = rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
        
        header('Location: ' . $url, true, $statusCode);
        exit;
    }
    
    /**
     * Generate URL dari path
     */
    public static function url($path = '') {
        $baseUrl = BASE_URL ?? '/';
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
    
    /**
     * Get current URL
     */
    public static function currentUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Get request parameter
     */
    public static function getParam($key, $default = null) {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }
        return $default;
    }
    
    /**
     * Get POST data
     */
    public static function getPost($key, $default = null) {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
        return $default;
    }
    
    /**
     * Get JSON body dari request
     */
    public static function getJsonBody() {
        $body = file_get_contents('php://input');
        return json_decode($body, true);
    }
    
    /**
     * Return JSON response
     */
    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}