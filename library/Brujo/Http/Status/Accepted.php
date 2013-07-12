<?php
/**
 * HTTP status: 202 Accepted
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Status
 */

namespace Brujo\Http\Status;

use Brujo\Http\Status;

/**
 * HTTP Accepted
 */
class Accepted extends Status
{
    /**
     * @var int
     */
    protected $code = 202;

    /**
     * @var string
     */
    protected $message = 'Accepted';
}
