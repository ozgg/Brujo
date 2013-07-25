<?php
/**
 * 
 * 
 * Date: 07.07.13
 * Time: 15:48
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Helper
 */

namespace Brujo\Helper;
 
use Brujo\Http\Router;
use Brujo\Helper;

class Link extends Helper
{
    public function render($routeName, $text = '', $input = [])
    {
        try {
            $route = $this->getRoute($routeName);
            if ($text == '') {
                $text = $routeName;
            }

            $uri    = $route->assemble($input);
            $format = '<a href="%s">%s</a>';
            $result = sprintf($format, $uri, $this->escape($text));
        } catch (\RuntimeException $e) {
            $result = $e->getMessage();
        }

        return $result;
    }

    /**
     * @param $name
     * @return \Brujo\Http\Route
     * @throws \RuntimeException
     */
    protected function getRoute($name)
    {
        $router = $this->extractDependency('router');
        if ($router instanceof Router) {
            $route = $router->getRoute($name);
        } else {
            throw new \RuntimeException('Cannot extract router');
        }

        return $route;
    }
}
