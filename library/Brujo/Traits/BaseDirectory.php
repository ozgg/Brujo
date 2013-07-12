<?php
/**
 * Base directory for something
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Traits
 */

namespace Brujo\Traits;

/**
 * Object uses some base directory
 */
trait BaseDirectory 
{
    /**
     * Path to base directory
     *
     * @var string
     */
    protected $baseDirectory;

    /**
     * Get base directory
     *
     * @return string
     */
    public function getBaseDirectory()
    {
        return $this->baseDirectory;
    }

    /**
     * Set base directory
     *
     * @param string $baseDirectory
     * @throws \RuntimeException
     * @return $this
     */
    public function setBaseDirectory($baseDirectory)
    {
        if (!is_dir($baseDirectory)) {
            $error = "Directory {$baseDirectory} does not exist";
            throw new \RuntimeException($error);
        }

        $this->baseDirectory = realpath($baseDirectory);

        return $this;
    }
}
