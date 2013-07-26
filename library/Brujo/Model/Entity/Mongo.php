<?php
/**
 * 
 * 
 * Date: 26.07.13
 * Time: 16:54
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Model\Entity
 */

namespace Brujo\Model\Entity;
 
use Brujo\Model\Entity;
use Brujo\Model\Mapper;

class Mongo extends Entity
{
    /**
     * ПК в Mongo
     *
     * @var \MongoId
     * @column _id
     */
    protected $mongoId;

    /**
     * Get mapper
     *
     * @return Mapper
     */
    public function getMapper()
    {
        // TODO: Implement getMapper() method.
    }

    /**
     * Получить MongoId
     *
     * @return \MongoId
     */
    public function getMongoId()
    {
        return $this->mongoId;
    }

    /**
     * Задать MongoId
     *
     * @param \MongoId $mongoId
     * @return Entity
     */
    public function setMongoId($mongoId)
    {
        if ($mongoId instanceof \MongoId) {
            $this->mongoId = $mongoId;
        } elseif (isset($mongoId['$id'])) {
            $this->mongoId = new \MongoId($mongoId['$id']);
        } else {
            $this->mongoId = new \MongoId($mongoId);
        }

        return $this;
    }
}
 