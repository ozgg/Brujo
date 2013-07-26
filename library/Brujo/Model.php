<?php
/**
 * 
 * 
 * Date: 26.07.13
 * Time: 13:37
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo
 */

namespace Brujo;
 
abstract class Model
{
    /**
     * Constant value exists in class?
     *
     * @param string $prefix constant prefix
     * @param mixed $check value to check
     * @return bool
     */
    public function constantValueExists($prefix, $check)
    {
        $exists    = false;
        $constants = (new \ReflectionClass($this))->getConstants();
        foreach ($constants as $constant => $value) {
            if (strpos($constant, $prefix) === 0) {
                if ($check === $value) {
                    $exists = true;
                    break;
                }
            }
        }

        return $exists;
    }
}
 