<?php
/**
 *
 *
 * Date: 26.07.13
 * Time: 16:26
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Model\Mapper
 */

namespace Brujo\Model\Mapper;

use Brujo\Model;
use Brujo\Traits\Dependency\Container;

class MongoMapper extends Model\Mapper
{
    /**
     * @var \MongoClient
     */
    protected $connection;

    /**
     * @var \MongoCollection
     */
    protected $collection;

    /**
     * @var string
     */
    protected $collectionName;

    /**
     * @var string
     */
    protected $databaseName;

    /**
     * @var array
     */
    protected $criteria = [];

    /**
     * @var array
     */
    protected $sort = [];

    /**
     * @var int
     */
    protected $limit = 0;

    /**
     * @param array $criteria
     * @return MongoMapper
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * @return array
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param int $limit
     * @return MongoMapper
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $offset
     * @return MongoMapper
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param array $sort
     * @return MongoMapper
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return array
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @var int
     */
    protected $offset = 0;

    public function connect($server, array $options = [])
    {
        $this->setConnection(new \MongoClient($server, $options));
    }

    public function save(Model\Entity $entity)
    {
        /** @var Model\Entity\Mongo $entity */
        $this->checkEntityClass($entity);
        $entity->validate();
        $options = ['safe' => true, 'upsert' => false, 'multiple' => false];
        $storage = $this->getStorage();
        if ($entity->isNew()) {
            $this->onCreate($entity);
            $this->releaseTriggers($entity);

            $options['upsert'] = true;

            $data   = $entity->getRawData(false);
            $result = $storage->save($data, $options);

            $entity->setIsNew(false);
        } else {
            $this->onUpdate($entity);
            $this->releaseTriggers($entity);

            $criteria = ['_id' => $entity->getMongoId()];
            $update   = $entity->getFieldsForQuery();
            $unset    = [];
            unset($update['_id'], $update['id']);
            foreach (array_keys($update) as $key) {
                if (is_null($update[$key])) {
                    unset($update[$key]);
                    $unset[$key] = true;
                }
            }

            $result = $storage->update(
                $criteria, ['$set' => $update, '$unset' => $unset], $options
            );
        }

        if (!$result) {
            throw new \RuntimeException('Cannot save entity');
        }
        $this->releaseTriggers($entity, false);
    }

    public function destroy(Model\Entity $entity)
    {
        $this->checkEntityClass($entity);
        /** @var Model\Entity\Mongo $entity */
        $this->onDestroy($entity);
        $storage = $this->getStorage();
        $storage->remove(['_id' => $entity->getMongoId()]);
    }

    public function getCursor(array $fields = [])
    {
        if (!$this->collection instanceof \MongoCollection) {
            $this->collection = $this->connection->selectCollection(
                $this->getDatabaseName(), $this->getCollectionName()
            );
        }

        return $this->collection->find($this->criteria, $fields);
    }

    /**
     * @return \MongoClient
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param \MongoClient $connection
     * @return MongoMapper
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @return string
     */
    public function getCollectionName()
    {
        return $this->collectionName;
    }

    /**
     * @param string $collectionName
     * @return MongoMapper
     */
    public function setCollectionName($collectionName)
    {
        $this->collectionName = $collectionName;

        return $this;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }

    /**
     * @param string $databaseName
     * @return MongoMapper
     */
    public function setDatabaseName($databaseName)
    {
        $this->databaseName = $databaseName;

        return $this;
    }

    protected function checkEntityClass(Model\Entity $entity)
    {
        if (!$entity instanceof Model\Entity\Mongo) {
            throw new \InvalidArgumentException('Invalid entity type');
        }
    }

    protected function getStorage()
    {
        return $this->connection->selectCollection(
            $this->databaseName, $this->collectionName
        );
    }
}
 