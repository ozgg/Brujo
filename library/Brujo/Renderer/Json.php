<?php
/**
 * JSON renderer
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Renderer
 */

namespace Brujo\Renderer;
 
use Brujo\Renderer;

/**
 * JSON renderer
 */
class Json extends Renderer
{
    /**
     * @return string
     */
    public function render()
    {
        return json_encode($this->getParameters(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return 'application/json;charset=UTF-8';
    }
}
