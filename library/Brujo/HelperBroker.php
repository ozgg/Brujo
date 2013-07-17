<?php
/**
 * Helper broker
 *
 * Looks for helpers.
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo
 */

namespace Brujo;

use Brujo\Traits\HasParameters;

/**
 * Helper broker
 */
class HelperBroker 
{
    use HasParameters;

    /**
     * @var Helper[]
     */
    protected $storage = [];

    public function callHelper($helperName, $methodName, $arguments)
    {
        $helper = $this->getHelper($helperName);

        if ($helper instanceof Helper) {
            if (method_exists($helper, $methodName)) {
                $callback = [$helper, $methodName];
                $input    = (array) json_decode($arguments, true);

                if (is_callable($callback)) {
                    $result = call_user_func($callback, $input);
                } else {
                    $result = "Cannot call {$helperName}:{$methodName}";
                }
            } else {
                $result = "Helper {$helperName} has no method {$methodName}";
            }
        } else {
            $result = "Non-existent helper: {$helperName}";
        }

        return $result;
    }

    public function getHelper($helperName)
    {
        $helper = null;
        if (!isset($this->storage[$helperName])) {
            if (strpos($helperName, '.') > 0) {
                $parts      = explode('.', $helperName);
                $namespace  = array_shift($parts) . '\\Renderer';
                $helperName = implode('_', $parts);
            } else {
                $namespace = __NAMESPACE__;
            }

            $helperClass = $namespace . '\\Helper\\' . ucfirst($helperName);
            if (class_exists($helperClass)) {
                $helper = new $helperClass;
                if ($helper instanceof Helper) {
                    $helper->setParameters($this->getParameters());
                    $this->storage[$helperName] = $helper;
                }
            }
        } else {
            $helper = $this->storage[$helperName];
        }

        return $helper;
    }
}