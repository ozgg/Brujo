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

/**
 * Model property reflection
 */
class Property extends \ReflectionProperty
{
    use GetElement;

    /**
     * Doc-clock is parsed
     *
     * @var bool
     */
    protected $isParsed   = false;

    /**
     * Doc-block parameters (annotations)
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Get parameter
     *
     * @param string $parameter
     * @param mixed $default
     * @return mixed
     */
    public function get($parameter, $default = null)
    {
        $this->preCheck();

        return $this->getRawElement($this->parameters, $parameter, $default);
    }

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
     * Property has flag
     *
     * @param string $flag
     * @return bool
     */
    public function hasFlag($flag)
    {
        $this->preCheck();

        $result = false;
        if (array_key_exists($flag, $this->parameters)) {
            $value = $this->parameters[$flag];
            if (is_null($value)) {
                $result = true; // no value means flag is present, therefore set
            } elseif (is_bool($value)) {
                $result = $value;
            } else {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Parameter has value
     *
     * If parameter is array, checks if requested value is present in array.
     * Otherwise returns false.
     *
     * @param string $parameter
     * @param mixed $value
     * @return bool
     */
    public function hasValue($parameter, $value)
    {
        $this->preCheck();

        $result = false;
        if (isset($this->parameters[$parameter])) {
            $buffer = $this->parameters[$parameter];
            if (is_array($buffer)) {
                $result = in_array($value, $buffer);
            }
        }

        return $result;
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

    /**
     * Check if doc-block is parsed and parse if it is not.
     */
    protected function preCheck()
    {
        if (!$this->isParsed) {
            $this->parseDocBlock();
        }
    }

    /**
     * Parse doc-block
     */
    protected function parseDocBlock()
    {
        $block   = $this->getDocComment();
        $pattern = '/\s*\s+@([a-z]+)[ \t]*(.+)?\n/';

        preg_match_all($pattern, $block, $matches);

        foreach ($matches[1] as $i => $parameter) {
            $rawValue = trim($matches[2][$i]);
            $value    = $this->parseValue($rawValue);

            if (array_key_exists($parameter, $this->parameters)) {
                settype($this->parameters[$parameter], 'array');
                $this->parameters[$parameter][] = $value;
            } else {
                $this->parameters[$parameter] = $value;
            }
        }

        $this->isParsed = true;
    }

    /**
     * Parse annotation value
     *
     * Analyzes string and parses it, casting to bool, array, null or leaving
     * as is.
     * If the string is quoted, returns its contents.
     *
     * @param string $value
     * @return mixed
     */
    protected function parseValue($value)
    {
        $boolean = array(
            'true'  => true,
            'false' => false,
            'yes'   => true,
            'no'    => false,
            'on'    => true,
            'off'   => false,
        );
        if (preg_match('/(\'|").*\\1$/', $value)) {
            $result = mb_substr($value, 1, -1);
        } elseif (isset($boolean[$value])) {
            $result = $boolean[$value];
        } elseif ($value === '') {
            $result = null;
        } elseif (strpos($value, ',') !== false) {
            $array = explode(',', $value);
            array_walk($array, function(&$v) { $v = trim($v); });
            $result = $array;
        } else {
            $result = $value;
        }

        return $result;
    }
}
