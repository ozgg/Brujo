<?php
/**
 * Class loader
 *
 * Auto-loads classes
 * 
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo
 */

namespace Brujo;

/**
 * Loader
 */
class Loader
{
    /**
     * Load class
     *
     * @param string $className
     */
    public static function load($className)
    {
        static $library = '';   // Path to library root

        if ($library == '') {
            $library = realpath(__DIR__ . '/../');
        }

        // Get FQCN
        $parts = explode('\\', $className);
        $path  = $library . DIRECTORY_SEPARATOR;

        // Does class have a namespace?
        if (count($parts) > 1) {
            $file  = \array_pop($parts);
            $path .= implode(DIRECTORY_SEPARATOR, $parts) . DIRECTORY_SEPARATOR;
        } else {
            $file = $className;
        }

        // Add extension to path
        $file = $path . $file . '.php';
        if (is_file($file)) {
            require $file;
        }
    }
}
