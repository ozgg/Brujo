<?php
/**
 * HTTP-response
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http
 */

namespace Brujo\Http;

use Brujo\Traits\Http\HasHeaders;

/**
 * HTTP-response
 */
class Response
{
    use HasHeaders;

    /**
     * HTTP status
     *
     * @var Status
     */
    protected $status;

    /**
     * Content type
     *
     * Will be sent as header "Content-Type"
     *
     * @var string
     */
    protected $contentType = 'text/plain;charset=UTF-8';

    /**
     * Body
     *
     * @var string
     */
    protected $body = '';

    /**
     * Constructor
     *
     * Accepts response body as argument
     *
     * @param string $body
     */
    public function __construct($body = '')
    {
        $this->setBody($body);
    }

    /**
     * Send response
     *
     * Sends HTTP headers and echoes body
     */
    public function send()
    {
        if ($this->status instanceof Status) {
            header('HTTP/1.1 ' . $this->status);
        }

        header("Content-type: {$this->contentType}");

        foreach ($this->getHeaders() as $header => $value) {
            header("{$header}: {$value}");
        }

        if ($this->body != '') {
            echo $this->body;
        }
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Response
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set status
     *
     * @param Status $status
     * @return Response
     */
    public function setStatus(Status $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set content type
     *
     * @param string $type
     * @return Response
     */
    public function setContentType($type)
    {
        $this->contentType = $type;

        return $this;
    }

    /**
     * Get content type
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }
}
