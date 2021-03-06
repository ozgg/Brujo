<?php
/**
 * Error 501
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Error
 */

namespace Brujo\Http\Error;

use Brujo\Http\Error;
use Brujo\Http\Status\NotImplemented as Status;

/**
 * Error 501
 */
class NotImplemented extends Error
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
