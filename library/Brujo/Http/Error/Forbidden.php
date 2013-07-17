<?php
/**
 * Error 403
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Error
 */

namespace Brujo\Http\Error;
 
use Brujo\Http\Error;
use Brujo\Http\Status\Forbidden as Status;

/**
 * Error 403
 */
class Forbidden extends Error
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
