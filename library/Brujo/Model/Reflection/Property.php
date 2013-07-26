<?php
/**
 * Model property reflection
 *
 * Gives access to more convenient work with annotations
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Model\Reflection
 */

namespace Brujo\Model\Reflection;

use Brujo\Traits\GetElement;
use Brujo\Traits\Reflection\DocBlock;

/**
 * Model property reflection
 */
class Property extends \ReflectionProperty
{
    use DocBlock;

    /**
     * Get column name in DB
     *
     * @return string
     */
    public function getColumn()
    {
        return $this->get('column', $this->getName());
    }

    /**
     * Get counter name for column in DB
     *
     * @return string
     */
    public function getCounter()
    {
        return $this->get('counter', $this->getName());
    }

    /**
     * Property is counter
     *
     * @return bool
     */
    public function isCounter()
    {
        return $this->hasFlag('counter');
    }

    /**
     * Property is column
     *
     * @return bool
     */
    public function isColumn()
    {
        return $this->hasFlag('column');
    }

    /**
     * Property (column) can be null
     *
     * @return bool
     */
    public function isNullable()
    {
        return $this->hasFlag('nullable');
    }
}
