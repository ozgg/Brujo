<?php
/**
 * Abstract renderer
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo
 */

namespace Brujo;

use Brujo\Traits;

/**
 * Abstract renderer
 */
abstract class Renderer
{
    use Traits\HasParameters,
        Traits\BaseDirectory,
        Traits\Dependency\Container;

    /**
     * Render as JSON
     */
    const FORMAT_JSON = 'json';

    /**
     * Render as HTML
     */
    const FORMAT_HTML = 'html';

    /**
     * Layout name
     *
     * @var string
     */
    protected $layoutName;

    /**
     * Helper broker
     *
     * @var HelperBroker
     */
    protected $helperBroker;

    /**
     * Render
     *
     * @return string
     */
    abstract public function render();

    /**
     * Get content type (for HTTP response header)
     *
     * @return string
     */
    abstract public function getContentType();

    /**
     * Factory
     *
     * @param string    $format
     * @param Container $container
     * @return Renderer\Html|Renderer\Json
     * @throws \InvalidArgumentException
     */
    public static function factory($format, Container $container)
    {
        switch ($format) {
            case static::FORMAT_JSON:
                $renderer = new Renderer\Json;
                break;
            case static::FORMAT_HTML:
                $renderer = new Renderer\Html;
                break;
            default:
                $error = "Invalid renderer format: {$format}";
                throw new \InvalidArgumentException($error);
        }

        $broker = new HelperBroker;
        $broker->setDependencyContainer($container);
        $renderer->setDependencyContainer($container);
        $renderer->setHelperBroker($broker);

        return $renderer;
    }

    /**
     * Get layout name
     *
     * @return string
     */
    public function getLayoutName()
    {
        return $this->layoutName;
    }

    /**
     * Set layout name
     *
     * @param string $layoutName
     * @return Renderer
     */
    public function setLayoutName($layoutName)
    {
        $this->layoutName = $layoutName;

        return $this;
    }

    /**
     * @return \Brujo\HelperBroker
     */
    public function getHelperBroker()
    {
        return $this->helperBroker;
    }

    /**
     * @param \Brujo\HelperBroker $helperBroker
     * @return Renderer
     */
    public function setHelperBroker(HelperBroker $helperBroker)
    {
        $this->helperBroker = $helperBroker;

        return $this;
    }
}
