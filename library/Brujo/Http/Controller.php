<?php
/**
 * Controller
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http
 */

namespace Brujo\Http;

use Brujo\Container;
use Brujo\Renderer;
use Brujo\Traits;

/**
 * Controller
 */
class Controller
{
    use Traits\HasParameters,
        Traits\Environment,
        Traits\Dependency\Container;

    /**
     * @var Status
     */
    protected $status;

    /**
     * Response/render format
     *
     * @var string
     */
    protected $format = Renderer::FORMAT_HTML;

    /**
     * Layout name to use
     *
     * @var string
     */
    protected $layoutName = 'layout';

    /**
     * View name to use
     *
     * @var string
     */
    protected $viewName = 'index';

    /**
     * Constructor
     *
     * Sets dependency container
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->setDependencyContainer($container);
    }

    /**
     * Initialize
     */
    public function init()
    {
    }

    /**
     * Perform operations before calling execute
     */
    public function preDispatch()
    {
    }

    /**
     * Perform operations after execute finished
     */
    public function postDispatch()
    {
    }

    /**
     * Execute action
     *
     * @param string $method HTTP method
     * @param string $action Action
     * @throws Error\NotFound
     */
    public function execute($method, $action)
    {
        $action .= 'Action';

        $callback   = [];
        $actionName = strtolower($method) . ucfirst($action);
        if (method_exists($this, $actionName)) {
            $callback = [$this, $actionName];
        } elseif (method_exists($this, $action)) {
            $callback = [$this, $action];
        }

        if (!empty($callback)) {
            call_user_func($callback);
            if (!$this->status instanceof Status) {
                $this->setStatus(new Status\Ok);
            }
        } else {
            throw new Error\NotFound("Cannot {$method} {$action} action");
        }
    }

    /**
     * Get response status
     *
     * @return \Brujo\Http\Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set response status
     *
     * @param \Brujo\Http\Status $status
     * @return Controller
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get response/render format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set response/render format
     *
     * @param string $format
     * @return Controller
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get layout name
     *
     * @return string
     */
    public function getLayoutName()
    {
        return $this->layoutName;
    }

    /**
     * Set layout name
     *
     * @param string $layoutName
     * @return Controller
     */
    public function setLayoutName($layoutName)
    {
        $this->layoutName = $layoutName;

        return $this;
    }

    /**
     * Get view name
     *
     * @return string
     */
    public function getViewName()
    {
        return $this->viewName;
    }

    /**
     * Set view name
     *
     * @param string $viewName
     * @return Controller
     */
    public function setViewName($viewName)
    {
        $this->viewName = $viewName;

        return $this;
    }

    /**
     * Get HTTP request
     *
     * @return Request
     * @throws \RuntimeException
     */
    protected function getRequest()
    {
        $request = $this->extractDependency('request');
        if (!$request instanceof Request) {
            throw new \RuntimeException('Cannot extract request');
        }

        return $request;
    }

    /**
     * Get used route
     *
     * @return Route
     * @throws \RuntimeException
     */
    protected function getRoute()
    {
        $route = $this->extractDependency('route');
        if (!$route instanceof Route) {
            throw new \RuntimeException('Cannot extract route');
        }

        return $route;
    }
}
