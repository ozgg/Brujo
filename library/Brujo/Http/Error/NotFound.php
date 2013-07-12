<?php
/**
 * Error 404
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Error
 */

namespace Brujo\Http\Error;

use Brujo\Http\Error;
use Brujo\Http\Status\NotFound as Status;

/**
 * Error 404
 */
class NotFound extends Error
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
