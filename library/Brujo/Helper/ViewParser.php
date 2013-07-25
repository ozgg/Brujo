<?php
/**
 *
 *
 * Date: 25.07.13
 * Time: 14:48
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Helper
 */

namespace Brujo\Helper;

use Brujo\Helper;
use Brujo\Http\Route;
use Brujo\Traits\BaseDirectory;

class ViewParser extends Helper
{
    use BaseDirectory;

    public function parse($view)
    {
        $file = $this->getBaseDirectory() . "/{$view}.html";
        $this->injectDependency('viewsPath', $this->getBaseDirectory());
        if (is_file($file)) {
            $content = file_get_contents($file);
            if (strpos($content, '{{content}}') !== false) {
                $main    = $this->renderMainContent($content);
                $content = str_replace('{{content}}', $main, $content);
            }
            $pattern = '/\{! (.+) !\}/';
            $result  = preg_replace_callback(
                $pattern, [$this, 'parseBlock'], $content
            );
        } else {
            throw new \RuntimeException("Cannot find view at {$file}");
        }

        return $result;
    }

    private function renderMainContent()
    {
        $route = $this->extractDependency('route');
        if ($route instanceof Route) {
            $view = strtolower($route->getControllerName());
            $view .= '/' . strtolower($route->getActionName());
            $result = $this->parse('views/' . $view);
        } else {
            $result = 'Cannot extract route.';
        }

        return $result;
    }
}
