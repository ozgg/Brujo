<?php
/**
 * 
 * 
 * Date: 25.07.13
 * Time: 15:21
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Helper
 */

namespace Brujo\Helper;
 
use Brujo\Helper;
use Brujo\Http\Route;

class Content extends Helper
{
    public function render()
    {
        $route  = $this->extractDependency('route');
        if ($route instanceof Route) {
            $parser = $this->getParser();
            $view   = strtolower($route->getControllerName());
            $view  .= '/' . strtolower($route->getActionName());
            $result = $parser->parse('views/' . $view);
        } else {
            $result = 'Cannot extract route.';
        }

        return $result;
    }
}
 