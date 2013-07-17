<?php
/**
 * Error 409
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Error
 */

namespace Brujo\Http\Error;

use Brujo\Http\Error;
use Brujo\Http\Status\Conflict as Status;

/**
 * Error 409
 */
class Conflict extends Error
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
