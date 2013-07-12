<?php
/**
 * Abstract error for HTTP application
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http
 */

namespace Brujo\Http;
 
use Brujo\Traits\Http\HasHeaders;

/**
 * Abstract HTTP error
 */
abstract class Error extends \RuntimeException
{
    use HasHeaders;

    /**
     * Get HTTP response status
     *
     * @return Status
     */
    abstract public function getStatus();
}
