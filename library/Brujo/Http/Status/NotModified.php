<?php
/**
 * HTTP status: 304 Not Modified
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Status
 */

namespace Brujo\Http\Status;
 
use Brujo\Http\Status;

/**
 * HTTP Not Modified
 */
class NotModified extends Status
{
    /**
     * @var int
     */
    protected $code = 304;

    /**
     * @var string
     */
    protected $message = 'Not Modified';
}
