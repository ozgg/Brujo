<?php
/**
 * Error 402
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Error
 */

namespace Brujo\Http\Error;
 
use Brujo\Http\Error;
use Brujo\Http\Status\PaymentRequired as Status;

/**
 * Error 402
 */
class PaymentRequired extends Error
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
