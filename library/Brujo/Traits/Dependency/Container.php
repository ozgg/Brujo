<?php
/**
 * Dependency container
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Traits\Dependency
 */

namespace Brujo\Traits\Dependency;

/**
 * Object uses dependency container
 */
trait Container 
{
    /**
     * Container
     *
     * @var \Brujo\Container
     */
    protected $dependencyContainer;

    /**
     * Get container
     *
     * @return \Brujo\Container
     */
    public function getDependencyContainer()
    {
        return $this->dependencyContainer;
    }

    /**
     * Set container
     *
     * @param \Brujo\Container $dependencyContainer
     * @return Container
     */
    public function setDependencyContainer(\Brujo\Container $dependencyContainer)
    {
        $this->dependencyContainer = $dependencyContainer;

        return $this;
    }

    /**
     * Inject dependency into container
     *
     * @param string $name
     * @param mixed $element
     */
    protected function injectDependency($name, $element)
    {
        $this->checkContainerState();
        $this->dependencyContainer->inject($name, $element);
    }

    /**
     * Extract dependency from container
     *
     * @param string $name
     * @return mixed|null
     */
    protected function extractDependency($name)
    {
        $this->checkContainerState();

        return $this->dependencyContainer->extract($name);
    }

    /**
     * Check if container is set
     *
     * @throws \RuntimeException
     */
    protected function checkContainerState()
    {
        if (!$this->dependencyContainer instanceof \Brujo\Container) {
            throw new \RuntimeException('Container is not set');
        }
    }

    /**
     * Require dependencies to be injected
     *
     * @throws \RuntimeException
     */
    protected function requireDependencies()
    {
        $this->checkContainerState();

        $injected    = $this->dependencyContainer->getKeys();
        $notInjected = [];
        foreach (func_get_args() as $argument) {
            if (is_array($argument)) {
                call_user_func_array(__METHOD__, $argument);
            } else {
                if (!in_array($argument, $injected)) {
                    $notInjected[] = $argument;
                }
            }
        }

        if (!empty($notInjected)) {
            $error = 'Required dependencies are not injected: '
                . implode(', ', $notInjected);
            throw new \RuntimeException($error);
        }
    }
}
