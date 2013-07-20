<?php
/**
 * Object has parameters
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Traits
 */

namespace Brujo\Traits;

/**
 * Object has parameters
 */
trait HasParameters
{
    use GetElement;

    /**
     * Parameters
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Get all parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set all parameters
     *
     * @param array $parameters
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get parameter with type casting
     *
     * Dots are used for nesting as separators
     *
     * @param string $name
     * @param mixed  $default
     * @return mixed|null
     */
    public function getParameter($name, $default = null)
    {
        if (strpos($name, '.') !== false) {
            $parts   = explode('.', $name);
            $storage = $this->parameters;
            $value   = $default;
            $part    = null;
            while (!empty($parts)) {
                $part = array_shift($parts);
                if (array_key_exists($part, $storage)) {
                    if (is_array($storage[$part]) && !empty($parts)) {
                        $storage = $storage[$part];
                    } else {
                        break;
                    }
                } else {
                    $part = null;
                    break;
                }
            }
            if (!is_null($part) && empty($parts)) {
                $value = $this->getElement($storage, $part);
            }
        } else {
            $value = $this->getElement($this->parameters, $name, $default);
        }

        return $value;
    }

    /**
     * Set parameter
     *
     * Dots are used for nesting as separators
     *
     * @param string $name
     * @param mixed  $value
     * @throws \RuntimeException
     * @return $this
     */
    public function setParameter($name, $value)
    {
        if (strpos($name, '.') !== false) {
            $parts   = explode('.', $name);
            $storage = &$this->parameters;
            $part    = null;
            while (!empty($parts)) {
                $part = array_shift($parts);
                if (!array_key_exists($part, $storage)) {
                    $storage[$part] = [];
                }
                if (is_array($storage[$part])) {
                    $storage = &$storage[$part];
                } else {
                    throw new \RuntimeException("Cannot set part {$part}");
                }
            }
            if (!is_null($part)) {
                $storage = $value;
            }
        } else {
            $this->parameters[$name] = $value;
        }

        return $this;
    }
}
