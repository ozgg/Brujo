<?php
/**
 * HTTP application
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

/**
 * HTTP Application
 */
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
     * Configuration directory
     *
     * @var string
     */
    protected $configDirectory = 'config';

    /**
     * Constructor
     *
     * Sets application directory and its name
     *
     * @param string $directory
     */
    public function __construct($directory)
    {
        $this->setBaseDirectory($directory);
        $this->setDependencyContainer(new Container);

        $name = ucfirst(strtolower(basename($this->getBaseDirectory())));
        $this->setName($name);
    }

    /**
     * Bootstrap
     *
     * Initialize minimal dependencies.
     */
    public function bootstrap()
    {
        $this->initRequest();
        $this->guessEnvironment();
        $this->initConfig();
        $this->initRouter();
    }

    /**
     * Run
     *
     * Execute controller based on request.
     */
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
        } catch (\Exception $e) {
            $this->injectDependency('error', $e);
            $this->renderError();
        }

        $this->postDispatch();
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Application
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Extract request from container or initialize it
     *
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
     * Extract router from container
     *
     * @throws \RuntimeException
     * @return Router
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
     * Import configuration from file
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

        if (is_file($file)) {
            $config = include $file;
        } else {
            throw new \RuntimeException("Cannot read config from file {$file}");
        }

        return (array) $config;
    }

    /**
     * Get configuration directory
     *
     * @return string
     */
    public function getConfigDirectory()
    {
        return $this->configDirectory;
    }

    /**
     * Set configuration directory
     *
     * @param string $configDirectory
     * @return Application
     */
    public function setConfigDirectory($configDirectory)
    {
        $this->configDirectory = $configDirectory;

        return $this;
    }

    /**
     * Things to do after application run is finished
     */
    protected function postDispatch()
    {
    }

    /**
     * Initialize request
     */
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
        $baseDir = $this->getBaseDirectory()
            . '/../../'
            . $this->getConfigDirectory();

        $config = new Configuration($baseDir);
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
        $router->loadCompacted($this->getBaseDirectory() . '/config/routes');

        $this->injectDependency('router', $router);
    }

    /**
     * Guess environment by requested host
     */
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

    /**
     * Execute controller
     *
     * @param string $name
     * @param string $method
     * @param string $action
     * @throws \RuntimeException
     */
    protected function executeController($name, $method, $action)
    {
        $name = ucfirst($name);
        $parts = [
            $this->getBaseDirectory(), 'controllers', "{$name}Controller",
        ];
        $file = implode(DIRECTORY_SEPARATOR, $parts) . '.php';
        if (is_file($file)) {
            include $file;
            $parts[0]  = $this->getName();
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

            $controller->setEnvironment($this->getEnvironment());
            $controller->init();
            $controller->preDispatch();
            $controller->execute($method, $action);
            $controller->postDispatch();

            $this->renderResponse($controller);
        } else {
            throw new \RuntimeException("Cannot load controller from {$file}");
        }
    }

    /**
     * Render response
     *
     * @param Controller $controller
     */
    protected function renderResponse(Controller $controller)
    {
        $renderer = Renderer::factory(
            $controller->getFormat(), $this->getDependencyContainer()
        );
        $renderer->setParameters($controller->getParameters());

        $response = new Response($renderer->render());
        $response->setContentType($renderer->getContentType());
        $response->send();

        $this->injectDependency('response', $response);
    }

    /**
     * Render error
     */
    protected function renderError()
    {
        $error = $this->extractDependency('error');
        if ($error instanceof \Exception) {
            $renderer = new Renderer\Json;
            $renderer->setDependencyContainer($this->getDependencyContainer());
            $renderer->setParameter('error', $error->getMessage());
            if (!$this->isProduction()) {
                $renderer->setParameter('trace', $error->getTrace());
            }

            $response = new Response($renderer->render());
            if ($error instanceof Error) {
                $response->setStatus($error->getStatus());
            } else {
                $response->setStatus(new InternalServerError);
            }
            $response->setContentType($renderer->getContentType());
            $response->send();

            $this->injectDependency('response', $response);
        } else {
            $response = new Response('Unexpected Error');
            $response->send();

            $this->injectDependency('response', $response);
        }
    }
}
