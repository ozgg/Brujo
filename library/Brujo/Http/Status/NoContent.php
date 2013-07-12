<?php
/**
 * HTTP status: 204 No Content
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Status
 */

namespace Brujo\Http\Status;
 
use Brujo\Http\Status;

/**
 * HTTP No Content
 */
class NoContent extends Status
{
    /**
     * @var int
     */
    protected $code = 204;

    /**
     * @var string
     */
    protected $message = 'No Content';
}
