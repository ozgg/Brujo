<?php
/**
 * Universal entity
 *
 * Represents entity that can be stored in database.
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Model
 */

namespace Brujo\Model;
 
use Brujo\Model;

/**
 * Universal Entity
 */
abstract class Entity extends Model
{
    /**
     * Metadata
     *
     * @var array
     */
    protected static $metadata = [];

    /**
     * Triggers
     *
     * @var array
     */
    protected $triggers = [];

    /**
     * Instance is new
     *
     * @var bool
     */
    protected $isNew = false;

    /**
     * Get mapper
     *
     * @return Mapper
     */
    abstract public function getMapper();

    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->init($data);

        if (!empty($data)) {
            $this->setFromArray($data);
        }
    }

    /**
     * Cast to string
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->getRawData(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get metadata
     *
     * Returns array with model structure data. If there is no data yet,
     * collects and caches it.
     *
     * @throws \RuntimeException
     * @return array
     */
    public function getMetadata()
    {
        $class = get_class($this);
        if (empty(static::$metadata[$class])) {
            $metadata   = [];
            $reflection = new Reflection($this);

            foreach ($reflection->getProperties() as $property) {
                if ($property->isStatic()) {
                    continue;
                }
                $name = $property->getName();
                if ($property->isColumn()) {
                    $column = $property->getColumn();

                    $metadata['columns'][$column]  = $name;
                    $metadata['nullable'][$column] = $property->isNullable();
                    $metadata['types'][$column]    = $property->get('var');
                } elseif ($property->isCounter()) {
                    $metadata['counters'][$property->getCounter()] = $name;
                }
            }

            static::$metadata[$class] = $metadata;
        } else {
            $metadata = static::$metadata[$class];
        }

        if (empty($metadata)) {
            $error = "Cannot get metadata for model {$class}";
            throw new \RuntimeException($error);
        }

        return $metadata;
    }

    /**
     * Instance is new
     *
     * @return bool
     */
    public function isNew()
    {
        return $this->isNew;
    }

    /**
     * Get column values for query
     *
     * @return array
     */
    public function getFieldsForQuery()
    {
        return $this->getFieldsForQueryFromMetadata('columns');
    }

    /**
     * Get counter values for query
     *
     * @return array
     */
    public function getCountersForQuery()
    {
        return $this->getFieldsForQueryFromMetadata('counters');
    }

    /**
     * Get raw data
     *
     * @param bool $nulls keep nulls
     * @return array
     */
    public function getRawData($nulls = true)
    {
        $data = $this->getFieldsForQuery() + $this->getCountersForQuery();

        if (!$nulls) {
            foreach (array_keys($data) as $key) {
                if (is_null($data[$key])) {
                    unset($data[$key]);
                }
            }
        }

        return $data;
    }

    /**
     * Set values from array
     *
     * @param array $data
     * @throws \RuntimeException
     */
    public function setFromArray(array $data)
    {
        $metadata  = $this->getMetadata();
        $className = get_class($this);
        $excessive = [];
        foreach ($data as $column => $value) {
            $setter = '';
            if (isset($metadata['counters'][$column])) {
                settype($value, 'int');
                $setter = 'set' . ucfirst($metadata['counters'][$column]);
            } elseif (isset($metadata['columns'][$column])) {
                $setter = 'set' . ucfirst($metadata['columns'][$column]);
            }
            if ($setter != '') {
                if (method_exists($this, $setter)) {
                    call_user_func([$this, $setter], $value);
                } else {
                    $error = "Undefined method {$className}#{$setter}";
                    throw new \RuntimeException($error);
                }
            } else {
                $excessive[] = $column;
            }
        }
        if (!empty($excessive)) {
            $error = "Non-existent properties in model {$className}"
                . ': ' . implode(', ', $excessive);
            error_log($error);
        }
    }

    /**
     * Validate values of model fields
     *
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function validate()
    {
        $metadata = $this->getMetadata();
        $invalid  = ['null' => [], 'value' => []];
        $class    = get_class($this);
        $types    = ['int', 'string', 'float', 'bool', 'array'];
        foreach ($metadata['columns'] as $column => $property) {
            $validator = 'validate' . ucfirst($property);
            $getter    = 'get' . ucfirst($property);
            if (method_exists($this, $validator)) {
                $isValid = call_user_func([$this, $validator]);
                if (!$isValid) {
                    $invalid['values'][] = $column;
                }
            } else {
                if (!method_exists($this, $getter)) {
                    $error = "Undefined method {$class}::{$getter}()";
                    throw new \RuntimeException($error);
                }
                $value = call_user_func([$this, $getter]);
                if (is_null($value)) {
                    if (!$metadata['nullable'][$column]) {
                        $invalid['null'][] = $column;
                    }
                } else {
                    $validType = $metadata['types'][$column];
                    if (in_array($validType, $types)) {
                        $columnType = gettype($value);
                        $transform  = [
                            'integer' => 'int',
                            'boolean' => 'bool',
                            'double'  => 'float',
                        ];
                        if (isset($transform[$columnType])) {
                            $columnType = $transform[$columnType];
                        }
                        if ($columnType != $validType) {
                            $invalid['values'][] = $column;
                        }
                    }
                }
            }
        }

        if (!empty($invalid['null'])) {
            $error = "Forbidden null values for columns in model {$class}: "
                . implode(', ', $invalid['null']);
            throw new \DomainException($error);
        }

        if (!empty($invalid['values'])) {
            $error = "Invalid values for columns in model {$class}: "
                . implode(', ', $invalid['values']);
            throw new \DomainException($error);
        }
    }

    /**
     * Set triggers
     *
     * @param array $triggers
     * @return Entity
     */
    public function setTriggers(array $triggers)
    {
        $this->triggers = $triggers;

        return $this;
    }

    /**
     * Get triggers
     *
     * @return array
     */
    public function getTriggers()
    {
        return $this->triggers;
    }

    /**
     * Alias to isNew()
     *
     * @return boolean
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * Set isNew flag
     *
     * @param boolean $isNew
     * @return Entity
     */
    public function setIsNew($isNew)
    {
        $this->isNew = (bool) $isNew;

        return $this;
    }

    /**
     * Get fields of certain type for query
     *
     * @param string $type
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @return array
     */
    protected function getFieldsForQueryFromMetadata($type)
    {
        if (!in_array($type, ['columns', 'counters'])) {
            $error = 'Invalid type for fields getter';
            throw new \InvalidArgumentException($error);
        }
        $metadata = $this->getMetadata();
        $result   = [];
        $class    = get_class($this);
        if (isset($metadata[$type])) {
            foreach ($metadata[$type] as $column => $property) {
                $getter = 'get' . ucFirst($property);
                if (!method_exists($this, $getter)) {
                    $error = "Undefined method {$class}::{$getter}()";
                    throw new \RuntimeException($error);
                }
                $result[$column] = call_user_func([$this, $getter]);
            }
        }

        return $result;
    }

    /**
     * Set trigger
     *
     * @param string $name
     * @param bool $beforeSave
     */
    protected function setTrigger($name, $beforeSave = true)
    {
        $this->triggers[$name] = $beforeSave;
    }

    /**
     * Initialize
     *
     * @param array $data
     */
    protected function init(array &$data)
    {
        // Child objects can normalize data array here
    }
}
 