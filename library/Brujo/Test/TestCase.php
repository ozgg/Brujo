<?php
/**
 * Test case
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Test
 */

namespace Brujo\Test;

/**
 * Test case
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Path to tests root directory
     *
     * @var string
     */
    protected $rootPath = '../../../tests';

    /**
     * Get sample for test from file
     *
     * @param string $name
     * @throws \RuntimeException
     * @return mixed
     */
    protected function getSample($name)
    {
        $sample = null;
        $path   = __DIR__ . DIRECTORY_SEPARATOR . $this->rootPath
            . DIRECTORY_SEPARATOR . 'samples';
        $file   = realpath($path) . DIRECTORY_SEPARATOR . $name . '.php';
        if (is_file($file)) {
            $sample = include $file;
        } else {
            $error = "Cannot load sample {$name} from {$file}";
            throw new \RuntimeException($error);
        }

        return $sample;
    }
}
