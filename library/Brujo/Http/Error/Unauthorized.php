<?php
/**
 * Error 401
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Error
 */

namespace Brujo\Http\Error;
 
use Brujo\Http\Error;
use Brujo\Http\Status\Unauthorized as Status;

/**
 * Error 401
 */
class Unauthorized extends Error
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
