<?php
/**
 * HTTP status: 200 OK
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Status
 */

namespace Brujo\Http\Status;
 
use Brujo\Http\Status;

/**
 * HTTP Ok
 */
class Ok extends Status
{
    /**
     * @var int
     */
    protected $code = 200;

    /**
     * @var string
     */
    protected $message = 'OK';
}
