<?php
/**
 * Static route
 *
 * Represents static route without any parameters in URI
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Route
 */

namespace Brujo\Http\Route;

use Brujo\Http\Route;

/**
 * Static route
 */
class StaticRoute extends Route
{
    /**
     * Assemble URI
     *
     * @return string
     */
    public function assemble()
    {
        return $this->getUri();
    }

    /**
     * Get regEx pattern to match
     *
     * @return string
     */
    public function getMatch()
    {
        return $this->getUri();
    }
}
