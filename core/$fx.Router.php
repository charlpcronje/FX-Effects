<?php
namespace fx\core;

/**
 * Router.php
 *
 * Handles routing for FX applications, mapping URIs to callbacks.
 */

/**
 * Class Router
 *
 * Handles routing for FX applications, mapping URIs to callbacks.
 */
class Router {
    /**
     * @var array Stores the registered routes.
     * @access private
     */
    private $routes = [];

    /**
     * Add a GET route.
     *
     * @param string $uri The URI to match.
     * @param callable $callback The function to execute when matched.
     * @access public
     */
    public function get($uri, $callback) {
        $this->add('GET', $uri, $callback);
    }

    /**
     * Add a POST route.
     *
     * @param string $uri The URI to match.
     * @param callable $callback The function to execute when matched.
     * @access public
     */
    public function post($uri, $callback) {
        $this->add('POST', $uri, $callback);
    }

    /**
     * Add a route for any method.
     *
     * @param string $method The HTTP method (GET, POST, etc.).
     * @param string $uri The URI to match.
     * @param callable $callback The function to execute when matched.
     * @access public
     */
    public function add($method, $uri, $callback) {
        $this->routes[] = [
            'method'   => strtoupper($method),
            'uri'      => $uri,
            'callback' => $callback,
        ];
    }

    /**
     * Listens for incoming requests and dispatches to the matching route.
     *
     * @access public
     */
    public function listen() {
        // Use only the path component.
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            // Convert route URI with parameters (e.g. /user/:id) to a regex.
            $pattern = preg_replace('/:[^\/]+/', '([^/]+)', $route['uri']);
            $pattern = '#^' . $pattern . '$#';

            if (!preg_match($pattern, $requestUri, $matches)) {
                continue;
            }

            array_shift($matches); // Remove the full match.
            call_user_func_array($route['callback'], $matches);
            return;
        }

        // No route matched; you may implement default controller routing here,
        // or simply send a 404.
        http_response_code(404);
        echo 'Route not found';
    }
}