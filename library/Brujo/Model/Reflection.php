<?php
/**
 * Reflection of model class
 *
 * Almost the same as \ReflectionClass, but uses extended property reflections
 * for more convenient work with annotations in models.
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Model
 */

namespace Brujo\Model;

use Brujo\Model\Reflection\Property;
use Brujo\Traits\Reflection\DocBlock;

/**
 * Reflection of model class
 */
class Reflection extends \ReflectionClass
{
    use DocBlock;

    /**
     * Get property
     *
     * @param string $name
     * @return Property
     */
    public function getProperty($name)
    {
        return new Property($this->getName(), $name);
    }

    /**
     * Get properties
     *
     * @param int|null $filter
     * @return Property[]
     */
    public function getProperties($filter = null)
    {
        $properties = [];
        if (is_null($filter)) {
            $filter = \ReflectionProperty::IS_PROTECTED
                + \ReflectionProperty::IS_PRIVATE
                + \ReflectionProperty::IS_PUBLIC
                + \ReflectionProperty::IS_STATIC;
        }

        foreach (parent::getProperties($filter) as $property) {
            $properties[] = new Property(
                $this->getName(), $property->getName()
            );
        }

        return $properties;
    }
}
