<?php
/**
 * Working with HTTP response headers
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Traits\Http
 */

namespace Brujo\Traits\Http;

use Brujo\Traits\GetElement;

/**
 * Object works with HTTP headers
 */
trait HasHeaders 
{
    use GetElement;

    /**
     * HTTP-headers
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Set all headers
     *
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Set header
     *
     * @param string $name
     * @param string $value
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = trim($value);
    }

    /**
     * Get all headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get header
     *
     * @param string $name
     * @return string|null
     */
    public function getHeader($name)
    {
        return $this->getElement($this->headers, $name, '');
    }

    /**
     * Add headers
     *
     * @param array $headers
     */
    public function addHeaders(array $headers)
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }
    }
}
