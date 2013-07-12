<?php
/**
 * HTTP status: 302 Found
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Status
 */

namespace Brujo\Http\Status;
 
use Brujo\Http\Status;

/**
 * HTTP Found
 */
class Found extends Status
{
    /**
     * @var int
     */
    protected $code = 302;

    /**
     * @var string
     */
    protected $message = 'Found';
}
