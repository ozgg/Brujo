<?php
/**
 * Route described by regular expression
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Route
 */

namespace Brujo\Http\Route;
 
use Brujo\Http\Route;

/**
 * Route described by regular expression
 */
class RegexRoute extends Route
{
    /**
     * Assemble URI
     *
     * @throws \RuntimeException
     * @throws \BadMethodCallException
     * @return string
     */
    public function assemble()
    {
        if ($this->reverse == '') {
            throw new \RuntimeException('Reverse pattern is not specified');
        }

        if (func_num_args() == 1) {
            $parameters = (array) func_get_arg(0);
        } else {
            $parameters = func_get_args();
        }

        if (substr_count($this->reverse, '%') != count($parameters)) {
            $error = 'Invalid number of parameters to assemble';
            throw new \BadMethodCallException($error);
        }

        return vsprintf($this->reverse, $parameters);
    }

    /**
     * Get regEx pattern to match
     *
     * @return string
     */
    public function getMatch()
    {
        return $this->getUri();
    }

    /**
     * Request by HTTP method $method
     *
     * @param string $method
     * @param string $uri
     */
    public function request($method, $uri)
    {
        parent::request($method, $uri);
        $parameters = [];

        preg_match("#{$this->uri}#", $uri, $matches);
        array_shift($matches);

        $drop = false;
        foreach ($matches as $parameter => $value) {
            if ($drop) {
                $drop = false;
            } else {
                if (!is_numeric($parameter)) {
                    $drop = true;
                }
                $parameters[$parameter] = $value;
            }
        }

        $this->setParameters($parameters);
    }
}
