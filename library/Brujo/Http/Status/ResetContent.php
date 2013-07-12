<?php
/**
 * HTTP status: 205 Reset Content
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Status
 */

namespace Brujo\Http\Status;
 
use Brujo\Http\Status;

/**
 * HTTP Reset Content
 */
class ResetContent extends Status
{
    /**
     * @var int
     */
    protected $code = 205;

    /**
     * @var string
     */
    protected $message = 'Reset Content';
}
