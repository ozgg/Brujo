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

use Brujo\Renderer;

class Html extends Renderer
{
    /**
     * @throws \RuntimeException
     * @return string
     */
    public function render()
    {
        $templatePath = $this->getBaseDirectory()
            . '/layouts/'
            . $this->getLayoutName()
            . '.phtml';

        if (!is_file($templatePath)) {
            throw new \RuntimeException("Cannot find template {$templatePath}");
        }

        ob_start();
        include $templatePath;

        return ob_get_clean();
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return 'text/html;charset=UTF-8';
    }
}
