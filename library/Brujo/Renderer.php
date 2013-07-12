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
     * View name
     *
     * @var string
     */
    protected $viewName;

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

        $renderer->setDependencyContainer($container);

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
     * Get view name
     *
     * @return string
     */
    public function getViewName()
    {
        return $this->viewName;
    }

    /**
     * Set view name
     *
     * @param string $viewName
     * @return Renderer
     */
    public function setViewName($viewName)
    {
        $this->viewName = $viewName;

        return $this;
    }
}
