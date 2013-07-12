<?php
/**
 * HTTP status: 400 Bad request
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Status
 */

namespace Brujo\Http\Status;
 
use Brujo\Http\Status;

/**
 * HTTP Bad Request
 */
class BadRequest extends Status
{
    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $message = 'Bad Request';
}
