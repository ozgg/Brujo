<?php
/**
 * Error 406
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Error
 */

namespace Brujo\Http\Error;

use Brujo\Http\Error;
use Brujo\Http\Status\NotAcceptable as Status;

/**
 * Error 406
 */
class NotAcceptable extends Error
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
