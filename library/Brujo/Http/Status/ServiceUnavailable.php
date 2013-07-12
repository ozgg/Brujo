<?php
/**
 * HTTP status: 503 Service Unavailable
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Status
 */

namespace Brujo\Http\Status;
 
use Brujo\Http\Status;

/**
 * HTTP Service Unavailable
 */
class ServiceUnavailable extends Status
{
    /**
     * @var int
     */
    protected $code = 503;

    /**
     * @var string
     */
    protected $message = 'Service Unavailable';
}
