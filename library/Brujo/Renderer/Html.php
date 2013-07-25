<?php
/**
 *
 *
 * Date: 07.07.13
 * Time: 12:32
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Renderer
 */

namespace Brujo\Renderer;

use Brujo\Helper\ViewParser;
use Brujo\Renderer;

class Html extends Renderer
{
    /**
     * @throws \RuntimeException
     * @return string
     */
    public function render()
    {
        /** @var ViewParser $viewParser */
        $viewParser = $this->getHelperBroker()->getHelper('viewParser');
        $viewParser->setBaseDirectory($this->getBaseDirectory());

        $layoutPath = 'layouts/' . $this->getLayoutName();

        return $viewParser->parse($layoutPath);
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return 'text/html;charset=UTF-8';
    }
}
