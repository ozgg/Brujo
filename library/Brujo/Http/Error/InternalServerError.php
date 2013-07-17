<?php
/**
 * Error 500
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Error
 */

namespace Brujo\Http\Error;
 
use Brujo\Http\Error;
use Brujo\Http\Status\InternalServerError as Status;

/**
 * Error 500
 */
class InternalServerError extends Error
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
