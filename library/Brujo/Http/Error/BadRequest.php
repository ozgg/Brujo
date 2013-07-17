<?php
/**
 * Error 400
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Error
 */

namespace Brujo\Http\Error;
 
use Brujo\Http\Error;
use Brujo\Http\Status\BadRequest as Status;

/**
 * Error 400
 */
class BadRequest extends Error
{
    /**
     * Get HTTP response status
     *
     * @return Status
     */
    public function getStatus()
    {
        return new Status;
    }
}
