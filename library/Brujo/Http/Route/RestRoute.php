<?php
/**
 * REST route
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Http\Route
 */

namespace Brujo\Http\Route;

use Brujo\Http\Route;
use Brujo\Inflection\Countable;

/**
 * REST route
 */
class RestRoute extends Route
{
    /**
     * Available element resources
     *
     * @var array
     */
    protected $resources = [];

    /**
     * Init from array
     *
     * @param array $data
     */
    public function initFromArray(array $data)
    {
        parent::initFromArray($data);
        if (isset($data['resources'])) {
            $this->setResources($data['resources']);
        }
    }

    /**
     * Assemble URI
     *
     * @throws \BadMethodCallException
     * @return string
     */
    public function assemble()
    {
        if (func_num_args() > 1) {
            $error = 'Too many arguments. Pass array|int or none';
            throw new \BadMethodCallException($error);
        }
        $uri = $this->uri;
        if (func_num_args() != 0) {
            $argument = func_get_arg(0);
            if (!is_array($argument)) {
                $uri .= '/' . intval($argument);
            } else {
                if (empty($argument['id'])) {
                    throw new \BadMethodCallException('Missing id index');
                }
                $uri .= '/' . intval($argument['id']);
                unset($argument['id']);
                if (!empty($argument)) {
                    if (count($argument) > 1) {
                        $error = 'Excessive number of resources';
                        throw new \BadMethodCallException($error);
                    }
                    $resource = key($argument);
                    if (!in_array($resource, $this->resources)) {
                        $error = "Invalid resource: {$resource}";
                        throw new \BadMethodCallException($error);
                    }
                    $uri .= '/' . $resource;
                    if (!is_null($argument[$resource])) {
                        $uri .= '/' . intval($argument[$resource]);
                    }
                }
            }
        }

        return $uri;
    }

    /**
     * Get regEx pattern to match
     *
     * @return string
     */
    public function getMatch()
    {
        $pattern = $this->uri;
        if (empty($this->resources)) {
            $pattern .= '(?:/(\d+))?';
        } else {
            $pattern .= '(?:/(\d+)(?:/('
                . implode('|', $this->resources)
                . ')(?:/(\d+))?)?)?';
        }

        return $pattern;
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

        preg_match_all("#{$this->getMatch()}#", $uri, $matches);

        $resource   = '';
        $elementId  = null;
        $resourceId = null;

        if (!empty($matches[2][0])) {
            $elementId  = $matches[1][0];
            $parameters = ['element_id' => $elementId];
            if (!empty($matches[3][0])) {
                $resourceId = $matches[3][0];

                $parameters['resource_id'] = $resourceId;
            }

            $resource = $matches[2][0];
        } elseif (!empty($matches[1][0])) {
            $elementId  = $matches[1][0];
            $parameters = [
                'element_id' => $elementId,
            ];
        } else {
            $parameters = [];
        }

        $this->mapActionName($elementId, $resource, $resourceId);
        $this->setParameters($parameters);
    }

    /**
     * Get available resources
     *
     * @return array
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Set available resources
     *
     * @param array $resources
     * @return RestRoute
     */
    public function setResources($resources)
    {
        $this->resources = $resources;

        return $this;
    }

    /**
     * Map action name based on ids and resource name
     *
     * @param int    $elementId
     * @param string $resource apply action on resource
     * @param int    $resourceId
     */
    protected function mapActionName($elementId, $resource, $resourceId)
    {
        $countable = new Countable;
        $plural    = pathinfo($this->uri, PATHINFO_BASENAME);
        $singular  = $countable->singularize($plural);
        $fallback  = ($plural == $singular);

        if (!empty($elementId)) {
            $actionName = $fallback ? 'Element' : ucfirst($singular);
            if (strlen($resource)) {
                $singular = $countable->singularize($resource);
                if ($singular == $resource) {
                    $actionName .= ucfirst($singular);
                    if (!empty($resourceId)) {
                        $actionName .= 'Resource';
                    } else {
                        $actionName .= 'Resources';
                    }
                } else {
                    if (!empty($resourceId)) {
                        $actionName .= ucfirst($singular);
                    } else {
                        $actionName .= ucfirst($resource);
                    }
                }
            }
        } else {
            $actionName = $fallback ? 'Collection' : ucfirst($plural);
        }

        $this->setActionName($actionName);
    }
}
