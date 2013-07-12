<?php
/**
 * HTTP status: 203 Non-Authoritative Infomation
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Status
 */

namespace Brujo\Http\Status;
 
use Brujo\Http\Status;

/**
 * HTTP Non-Authoritative Information
 */
class NonAuthoritative extends Status
{
    /**
     * @var int
     */
    protected $code = 203;

    /**
     * @var string
     */
    protected $message = 'Non-Authoritative Information';
}
