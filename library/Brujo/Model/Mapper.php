<?php
/**
 * Entity mapper
 *
 * Maps entity <-> database
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Model
 */

namespace Brujo\Model;

/**
 * Entity mapper
 */
abstract class Mapper
{
    protected $storage;

    abstract public function save(Entity $entity);

    abstract public function destroy(Entity $entity);

    /**
     * @return mixed
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @param mixed $storage
     * @return Mapper
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;

        return $this;
    }

    protected function onCreate(Entity $entity)
    {
    }

    protected function onUpdate(Entity $entity)
    {
    }

    protected function onDestroy(Entity $entity)
    {
    }

    /**
     * Release triggers
     *
     * @param Entity $entity
     * @param bool  $before
     * @throws \RuntimeException
     */
    protected function releaseTriggers(Entity $entity, $before = true)
    {
        $orphans = [];
        foreach ($entity->getTriggers() as $name => $type) {
            $trigger = 'trigger' . ucfirst($name);
            if (!method_exists($this, $trigger)) {
                $orphans[] = $name;
            } elseif ($type == $before) {
                call_user_func([$this, $trigger], $entity);
            }
        }

        if (!empty($orphans)) {
            $class = get_class($this);
            $error = "Define triggers in {$class}: " . implode(', ', $orphans);
            throw new \RuntimeException($error);
        }
    }
}
