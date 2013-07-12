<?php
/**
 * HTTP status: 401 Unauthorized
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Status
 */

namespace Brujo\Http\Status;
 
use Brujo\Http\Status;

/**
 * HTTP Unauthorized
 */
class Unauthorized extends Status
{
    /**
     * @var int
     */
    protected $code = 401;

    /**
     * @var string
     */
    protected $message = 'Unauthorized';
}
