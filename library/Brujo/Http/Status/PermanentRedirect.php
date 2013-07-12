<?php
/**
 * HTTP status: 308 Permanent Redirect
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Status
 */

namespace Brujo\Http\Status;
 
use Brujo\Http\Status;

/**
 * HTTP Permanent Redirect
 *
 */
class PermanentRedirect extends Status
{
    /**
     * @var int
     */
    protected $code = 308;

    /**
     * @var string
     */
    protected $message = 'Permanent Redirect';
}
