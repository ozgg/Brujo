<?php
/**
 * 
 * 
 * Date: 07.07.13
 * Time: 15:17
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo
 */

namespace Brujo;
 
use Brujo\Traits;

abstract class Helper
{
    use Traits\Dependency\Container, Traits\HasParameters;

    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
