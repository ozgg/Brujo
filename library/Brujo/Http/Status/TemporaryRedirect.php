<?php
/**
 * HTTP status: 307 Temporary Redirect
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Status
 */

namespace Brujo\Http\Status;
 
use Brujo\Http\Status;

/**
 * HTTP Temporary Redirect
 */
class TemporaryRedirect extends Status
{
    /**
     * @var int
     */
    protected $code = 307;

    /**
     * @var string
     */
    protected $message = 'Temporary Redirect';
}
