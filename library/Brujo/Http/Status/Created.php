<?php
/**
 * HTTP status: 201 Created
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Status
 */

namespace Brujo\Http\Status;
 
use Brujo\Http\Status;

/**
 * HTTP Created
 */
class Created extends Status
{
    /**
     * @var int
     */
    protected $code = 201;

    /**
     * @var string
     */
    protected $message = 'Created';
}
