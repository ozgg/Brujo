<?php
/**
 * 
 * 
 * Date: 25.07.13
 * Time: 15:50
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Helper
 */

namespace Brujo\Helper;
 
use Brujo\Helper;

class Title extends Helper
{
    private $stack = [];

    public function render($prefix, $delimiter = ' / ')
    {
        array_unshift($this->stack, $prefix);

        return implode($delimiter, $this->stack);
    }

    public function push($element)
    {
        $this->stack[] = $element;
    }
}
 