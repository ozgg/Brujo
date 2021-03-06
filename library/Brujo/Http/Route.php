<?php
/**
 * Abstract route
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http
 */

namespace Brujo\Http;

use Brujo\Http\Error\MethodNotAllowed;
use Brujo\Traits\HasParameters;

/**
 * Abstract Route
 */
abstract class Route
{
    use HasParameters;

    /**
     * Static route
     */
    const TYPE_STATIC = 'static';

    /**
     * Pattern route
     */
    const TYPE_PATTERN = 'pattern';

    /**
     * Regular expression route
     */
    const TYPE_REGEX = 'regex';

    /**
     * REST route
     */
    const TYPE_REST = 'rest';

    /**
     * HTTP get
     */
    const METHOD_GET = 'GET';

    /**
     * HTTP post
     */
    const METHOD_POST = 'POST';

    /**
     * HTTP put
     */
    const METHOD_PUT = 'PUT';

    /**
     * HTTP delete
     */
    const METHOD_DELETE = 'DELETE';

    /**
     * HTTP patch
     */
    const METHOD_PATCH = 'PATCH';

    /**
     * Type of route
     *
     * @var string
     */
    protected $type;

    /**
     * Name of route
     *
     * @var string
     */
    protected $name;

    /**
     * Matching request URI
     *
     * @var string
     */
    protected $uri;

    /**
     * Pattern for reverse building
     *
     * Route builds URI from given parameters by pattern.
     *
     * @var string
     */
    protected $reverse;

    /**
     * Allowed HTTP methods
     *
     * @var array
     */
    protected $methods = [];

    /**
     * Controller name to execute
     *
     * @var string
     */
    protected $controllerName;

    /**
     * Action name to execute
     *
     * @var string
     */
    protected $actionName;

    /**
     * Assemble URI
     *
     * @return string
     */
    abstract public function assemble();

    /**
     * Get regEx pattern to match
     *
     * @return string
     */
    abstract public function getMatch();

    /**
     * Constructor
     *
     * Make default methods allowed
     */
    public function __construct()
    {
        $this->methods = [
            static::METHOD_GET,
            static::METHOD_POST,
            static::METHOD_PUT,
            static::METHOD_PATCH,
            static::METHOD_DELETE,
        ];
    }

    /**
     * Route factory
     *
     * @param string $type
     * @throws \InvalidArgumentException
     * @return Route\PatternRoute|Route\RegexRoute|Route\RestRoute|Route\StaticRoute
     */
    public static function factory($type)
    {
        switch ($type) {
            case static::TYPE_STATIC:
                $route = new Route\StaticRoute;
                break;
            case static::TYPE_PATTERN:
                $route = new Route\PatternRoute;
                break;
            case static::TYPE_REGEX:
                $route = new Route\RegexRoute;
                break;
            case static::TYPE_REST:
                $route = new Route\RestRoute;
                break;
            default:
                $error = "Invalid route type: {$type}";
                throw new \InvalidArgumentException($error);
        }

        $route->setType($type);

        return $route;
    }

    /**
     * Decompress compacted data
     *
     * @param array $data
     */
    public function decompress(array $data)
    {
    }

    /**
     * Init from array
     *
     * @param array $data
     */
    public function initFromArray(array $data)
    {
        if (isset($data['name'])) {
            $this->setName($data['name']);
        }
        if (isset($data['uri'])) {
            $this->setUri($data['uri']);
        }
        if (isset($data['controller'])) {
            $this->setControllerName($data['controller']);
        }
        if (isset($data['action'])) {
            $this->setActionName($data['action']);
        }
        if (isset($data['methods'])) {
            $this->setMethods($data['methods']);
        }
        if (isset($data['reverse'])) {
            $this->setReverse($data['reverse']);
        }
        if (isset($data['parameters'])) {
            $this->setParameters($data['parameters']);
        }
    }

    /**
     * Route is static?
     *
     * @return bool
     */
    public function isStatic()
    {
        return $this->type == static::TYPE_STATIC;
    }

    /**
     * Request by HTTP method $method
     *
     * @param string $method
     * @param string $uri
     * @throws Error\MethodNotAllowed
     */
    public function request($method, $uri)
    {
        if (!in_array($method, $this->methods)) {
            $error = new MethodNotAllowed("Method {$method} is not allowed");
            $error->setHeader('Allow', implode(', ', $this->getMethods()));

            throw $error;
        }
    }

    /**
     * Set name of action in controller
     *
     * @param string $actionName
     * @return Route
     */
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;

        return $this;
    }

    /**
     * Get action name
     *
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * Set controller name
     *
     * @param string $controllerName
     * @return Route
     */
    public function setControllerName($controllerName)
    {
        $this->controllerName = $controllerName;

        return $this;
    }

    /**
     * Get controller name
     *
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * Set allowed HTTP methods
     *
     * @param array $methods
     * @return Route
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;

        return $this;
    }

    /**
     * Get allowed HTTP methods
     *
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Set route name
     *
     * @param string $name
     * @return Route
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get route name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set reverse pattern
     *
     * @param string $reverse
     * @return Route
     */
    public function setReverse($reverse)
    {
        $this->reverse = $reverse;

        return $this;
    }

    /**
     * Get reverse pattern
     *
     * @return string
     */
    public function getReverse()
    {
        return $this->reverse;
    }

    /**
     * Set matching request URI
     *
     * @param string $uri
     * @return Route
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get matching request URI
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get route type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set route type
     *
     * @param string $type
     * @return Route
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
