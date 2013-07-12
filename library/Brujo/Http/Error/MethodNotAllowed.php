<?php
/**
 * Error 405
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Error
 */

namespace Brujo\Http\Error;
 
use Brujo\Http\Error;
use Brujo\Http\Status\MethodNotAllowed as Status;

/**
 * Error 405
 */
class MethodNotAllowed extends Error
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
