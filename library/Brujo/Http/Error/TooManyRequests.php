<?php
/**
 * Error 429
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Error
 */

namespace Brujo\Http\Error;

use Brujo\Http\Error;
use Brujo\Http\Status\TooManyRequests as Status;

/**
 * Error 429
 */
class TooManyRequests extends Error
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
