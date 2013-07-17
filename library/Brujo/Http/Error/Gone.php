<?php
/**
 * Error 410
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Error
 */

namespace Brujo\Http\Error;
 
use Brujo\Http\Error;
use Brujo\Http\Status\Gone as Status;

/**
 * Error 410
 */
class Gone extends Error
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
