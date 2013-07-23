<?php
/**
 * Router
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http
 */

namespace Brujo\Http;

use Brujo\Http\Error\NotFound;

/**
 * Router
 */
class Router
{
    /**
     * Available routes
     *
     * @var Route[]
     */
    protected $routes = [];

    /**
     * Add route
     *
     * @param Route $route
     */
    public function addRoute(Route $route)
    {
        $this->routes[$route->getName()] = $route;
    }

    /**
     * Get route by name
     *
     * @param $name
     * @return Route
     * @throws \RuntimeException
     */
    public function getRoute($name)
    {
        if (isset($this->routes[$name])) {
            $route = $this->routes[$name];
        } else {
            throw new \RuntimeException("Cannot find route {$name}");
        }

        return $route;
    }

    /**
     * Get all routes
     *
     * @return Route[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    public function loadCompacted($baseDir)
    {
        if (is_dir($baseDir)) {
            foreach (scandir($baseDir) as $file) {
                if ($file[0] == '.') {
                    continue;
                }
                $type = pathinfo($file, PATHINFO_FILENAME);
                $data = include $baseDir . '/' . $file;
                switch ($type) {
                    case Route::TYPE_REST:
                        $this->decompressRestRoutes($data);
                        break;
                    default:
                        $error = "Invalid route type {$type}";
                        throw new \RuntimeException($error);
                }
            }
        }
    }

    /**
     * Import routes from array
     *
     * @param array $routes
     */
    public function import(array $routes)
    {
        foreach ($routes as $name => $data) {
            $type  = isset($data['type']) ? $data['type'] : Route::TYPE_STATIC;
            $route = Route::factory($type);
            $route->setName($name);
            $route->initFromArray($data);

            $this->addRoute($route);
        }
    }

    /**
     * Match URI to routes and get according route
     *
     * @param string $uri
     * @return Route
     * @throws \Brujo\Http\Error\NotFound
     */
    public function matchRequest($uri)
    {
        $match = null;
        $path  = '/' . trim(strtolower(parse_url($uri, PHP_URL_PATH)), '/');
        foreach ($this->routes as $route) {
            if ($route->isStatic()) {
                $found = $path == strtolower($route->getMatch());
            } else {
                $found = (preg_match("#{$route->getMatch()}#i", $path) > 0);
            }
            if ($found) {
                $match = $route;
                break;
            }
        }

        if (!$match instanceof Route) {
            throw new NotFound("Cannot match URI {$uri} against any route");
        }

        return $match;
    }

    protected function decompressRestRoutes(array $data)
    {
        foreach ($data as $uri => $routeInfo) {
            $prefix = '';
            $scope  = str_replace('/', '_', trim($uri, '/'));
            if ($scope != '') {
                $scope .= '_';
            }
            if (($uri == '') || ($uri[0] != '/')) {
                $uri = '/' . $uri;
            }
            if (substr($uri, -1) != '/') {
                $uri .= '/';
            }
            foreach ($routeInfo as $name => $resources) {
                if ($name == '_prefix') {
                    $prefix = $resources;
                } else {
                    $route = new Route\RestRoute;
                    $route->setName($scope . $name);
                    $route->setControllerName($prefix . ucfirst($name));
                    $route->setUri($uri . $name);
                    $route->setResources($resources);
                    $this->addRoute($route);
                }
            }
        }
    }
}
