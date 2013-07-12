<?php
/**
 * Configuration
 *
 * Stores application and components configuration
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo
 */

namespace Brujo;
 
use Brujo\Traits;

/**
 * Configuration
 */
class Configuration
{
    use Traits\HasParameters, Traits\BaseDirectory, Traits\Environment;

    /**
     * Constructor
     *
     * Accepts base directory path, where configuration files are stored
     *
     * @param string $baseDirectory
     */
    public function __construct($baseDirectory)
    {
        $this->setBaseDirectory($baseDirectory);
    }

    /**
     * Merge configuration arrays
     *
     * @param array $left
     * @param array $right
     * @return array
     */
    public static function merge(array $left, array $right)
    {
        foreach ($right as $key => $value) {
            if (array_key_exists($key, $left) && is_array($value)) {
                $left[$key] = self::merge($left[$key], $right[$key]);
            } else {
                $left[$key] = $value;
            }
        }

        return $left;
    }

    /**
     * Set environment
     *
     * @param string $environment
     * @return Configuration
     */
    public function setEnvironment($environment)
    {
        $this->environment = preg_replace('/[^a-z]/i', '', $environment);
        $this->initialize();

        return $this;
    }

    /**
     * Initialize
     *
     * Loads file with parameters corresponding to environment
     *
     * @throws \RuntimeException
     */
    private function initialize()
    {
        $file = $this->getBaseDirectory()
            . DIRECTORY_SEPARATOR
            . $this->getEnvironment() . '.php';

        if (is_file($file)) {
            $this->setParameters(include($file));
        } else {
            throw new \RuntimeException("Cannot load config file {$file}");
        }
    }
}
