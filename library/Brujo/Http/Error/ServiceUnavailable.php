<?php
/**
 * Error 503
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Error
 */

namespace Brujo\Http\Error;

use Brujo\Http\Error;
use Brujo\Http\Status\ServiceUnavailable as Status;

/**
 * Error 503
 */
class ServiceUnavailable extends Error
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
