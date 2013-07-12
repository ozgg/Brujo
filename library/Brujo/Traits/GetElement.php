<?php
/**
 * Trait for getting array elements with type casting
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Traits
 */

namespace Brujo\Traits;

/**
 * Getting array elements with type casting
 */
trait GetElement 
{
    /**
     * Get element of array by key with type casting
     *
     * @param array  $storage
     * @param string $name
     * @param mixed  $default
     * @return mixed|null
     */
    public function getElement(array $storage, $name, $default = null)
    {
        if (isset($storage[$name])) {
            $value = $storage[$name];
            if (is_int($default)) {
                settype($value, 'int');
            } elseif (is_float($default)) {
                settype($value, 'float');
            } elseif (is_string($default)) {
                settype($value, 'string');
            } elseif (is_bool($default)) {
                settype($value, 'bool');
            } elseif (is_array($default)) {
                settype($value, 'array');
            }
        } else {
            $value = $default;
        }

        return $value;
    }

    /**
     * Get raw element from storage
     *
     * @param array $storage
     * @param string $name
     * @return mixed|null
     */
    public function getRawElement(array $storage, $name)
    {
        if (isset($storage[$name])) {
            $value = $storage[$name];
        } else {
            $value = null;
        }

        return $value;
    }
}
