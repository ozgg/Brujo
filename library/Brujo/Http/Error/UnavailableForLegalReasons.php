<?php
/**
 * Error 451
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Error
 */

namespace Brujo\Http\Error;
 
use Brujo\Http\Error;
use Brujo\Http\Status\UnavailableForLegalReasons as Status;

/**
 * Error 451
 */
class UnavailableForLegalReasons extends Error
{
    /**
     * Get HTTP response status
     *
     * @return Status
     */
    final public function getStatus()
    {
        return new Status;
    }
}
