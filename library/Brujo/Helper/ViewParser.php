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
use Brujo\Traits\BaseDirectory;

class ViewParser extends Helper
{
    use BaseDirectory;

    public function parse($view)
    {
        $file = $this->getBaseDirectory() . "/{$view}.html";
        if (is_file($file)) {
            $result = file_get_contents($file);
        } else {
            throw new \RuntimeException("Cannot find view at {$file}");
        }

        return $result;
    }
}
