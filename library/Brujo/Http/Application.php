<?php
/**
 *
 *
 * Date: 27.06.13
 * Time: 15:31
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http
 */

namespace Brujo\Http;

use Brujo\Configuration;
use Brujo\Container;
use Brujo\Http\Status\InternalServerError;
use Brujo\Renderer;
use Brujo\Traits;

class Application
{
    use Traits\Environment,
        Traits\Dependency\Container,
        Traits\BaseDirectory;

    /**
     * Application name
     *
     * @var string
     */
    protected $name;

    /**
     * Constructor
     *
     * Sets application directory and bootstraps it
     *
     * @param string $directory
     */
    public function __construct($directory)
    {
        $name = ucfirst(strtolower(basename($directory)));

        $this->setBaseDirectory($directory);
        $this->setName($name);
        $this->setDependencyContainer(new Container);
        $this->initRequest();
        $this->guessEnvironment();
        $this->initConfig();
        $this->initRouter();
    }

    public function run()
    {
        $this->requireDependencies('router', 'request');
        try {
            $request = $this->getRequest();
            $router  = $this->getRouter();
            $route   = $router->matchRequest($request->getUri());
            $route->request($request->getMethod(), $request->getUri());
            $this->injectDependency('route', $route);

            $this->executeController(
                $route->getControllerName(),
                $request->getMethod(),
                $route->getActionName()
            );
        } catch (Error $e) {
            $this->fallback($e);
        } catch (\Exception $e) {
            $this->fallback($e);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Application
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @throws \RuntimeException
     * @return Request
     */
    public function getRequest()
    {
        $request = $this->extractDependency('request');
        if (!$request instanceof Request) {
            $this->initRequest();
            $request = $this->extractDependency('request');
        }
        if (!$request instanceof Request) {
            throw new \RuntimeException('Cannot extract request');
        }

        return $request;
    }

    /**
     * @return Router
     * @throws \RuntimeException
     */
    public function getRouter()
    {
        $router = $this->extractDependency('router');
        if (!$router instanceof Router) {
            throw new \RuntimeException('Cannot extract router');
        }

        return $router;
    }

    /**
     * Import config from file
     *
     * @param string $name
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @return array
     */
    public function importConfig($name)
    {
        if (strpos($name, '..') !== false) {
            $error = "Bad name for config to include: {$name}";
            throw new \InvalidArgumentException($error);
        }
        $path = realpath($this->baseDirectory . DIRECTORY_SEPARATOR . 'config');
        $file = $path . DIRECTORY_SEPARATOR . $name . '.php';

        if (file_exists($file) && is_file($file)) {
            $config = include $file;
        } else {
            throw new \RuntimeException("Cannot read config from file {$file}");
        }

        return (array) $config;
    }

    /**
     * Bootstrap application
     */
    protected function bootstrap()
    {
        // Bootstrap extending applications
    }

    protected function initRequest()
    {
        $request = new Request($_SERVER);
        $request->setPost($_POST);
        $request->setGet($_GET);
        $request->setBody(file_get_contents('php://input'));

        $this->injectDependency('request', $request);
    }

    /**
     * Initialize environment configuration
     */
    protected function initConfig()
    {
        $baseDir = $this->getBaseDirectory() . '/../../config';
        $config  = new Configuration($baseDir);
        $config->setEnvironment($this->getEnvironment());
        $this->injectDependency('config', $config);
    }

    /**
     * Initialize router
     */
    protected function initRouter()
    {
        $routes = $this->importConfig('routes');
        $router = new Router;
        $router->import($routes);

        $this->injectDependency('router', $router);
    }

    protected function guessEnvironment()
    {
        $request = $this->getRequest();
        $host    = $request->getHost();
        if (strpos($host, '.local') !== false) {
            $environment = 'development';
        } elseif (strpos($host, 'test.') !== false) {
            $environment = 'test';
        } elseif (strpos($host, 'staging.') !== false) {
            $environment = 'staging';
        } else {
            $environment = 'production';
        }

        $this->setEnvironment($environment);
    }

    protected function executeController($name, $method, $action)
    {
        $parts = [
            $this->baseDirectory,
            'controllers',
            "{$name}Controller"
        ];
        $file  = implode(DIRECTORY_SEPARATOR, $parts) . '.php';
        if (is_file($file)) {
            include $file;
            $parts[0] = $this->getName();
            $className = implode('\\', $parts);
            if (!class_exists($className)) {
                $error = "Cannot find controller {$className}";
                throw new \RuntimeException($error);
            }
            $controller = new $className($this->getDependencyContainer());
            if (!$controller instanceof Controller) {
                $error = "Invalid controller: {$className}";
                throw new \RuntimeException($error);
            }
        } else {
            throw new \RuntimeException("Cannot load controller from {$file}");
        }

        $controller->setEnvironment($this->getEnvironment());
        $controller->init();
        $controller->execute($method, $action);
        $this->renderResponse($controller);
    }

    protected function renderResponse(Controller $controller)
    {
        $renderer = Renderer::factory(
            $controller->getFormat(), $this->getDependencyContainer()
        );
        $renderer->setParameters($controller->getParameters());

        $response = new Response($renderer->render());
        $response->setContentType($renderer->getContentType());
        $response->send();
    }

    protected function renderError(Error $error)
    {
        $renderer = new Renderer\Json;
        $renderer->setDependencyContainer($this->getDependencyContainer());
        $renderer->setParameter('error', $error->getMessage());
        if (!$this->isProduction()) {
            $renderer->setParameter('trace', $error->getTrace());
        }

        $response = new Response($renderer->render());
        $response->setStatus($error->getStatus());
        $response->setContentType($renderer->getContentType());

        $response->send();
    }

    /**
     * Fallback if exception is caught
     *
     * @param \Exception $e
     */
    protected function fallback(\Exception $e)
    {
        $renderer = new Renderer\Json;
        if (!$this->isProduction()) {
            $renderer->setParameter('error', $e->getMessage());
            $renderer->setParameter('trace', $e->getTrace());
        } else {
            $renderer->setParameter('error', 'Internal server error');
        }

        $response = new Response($renderer->render());
        $response->setContentType($renderer->getContentType());
        $response->setStatus(new InternalServerError);

        $response->send();
    }
}