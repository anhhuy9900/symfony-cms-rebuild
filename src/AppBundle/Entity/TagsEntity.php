<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;


/**
 * @ORM\Entity
 * @ORM\Table(name="tags")
 */
class TagsEntity {

    /**
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="type_id", type="integer", length=11)
     */
    private $typeId = 0;

    /**
     * @ORM\Column(name="type", type="string", length=100, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(name="tagName", type="string", length=200, nullable=true)
     */
    private $tagName;

    /**
     * @ORM\Column(name="status", type="smallint", length=1)
     */
    private $status = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=false)
     */
    private $createdDate;

    /**
     * @param [int]
     */
    public function setID($id) {
        $this->id = $id;
    }

    /**
     * @return id
     */
    public function getID() {
        return $this->id;
    }

    /**
     * @param [int]
     */
    public function setTypeID($typeId) {
        $this->typeId = $typeId;
    }

    /**
     * @return typeId
     */
    public function getTypeID() {
        return $this->typeId;
    }

    /**
     * @param [int]
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return type
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param [string]
     */
    public function setTagName($tagName) {
        $this->tagName = $tagName;
    }

    /**
     * @return tagName
     */
    public function getTagName() {
        return $this->tagName;
    }

    /**
     * @param [int]
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return status
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param [datetime]
     */
    public function setCreatedDate($createdDate) {
        $this->createdDate = new \DateTime();
    }

    /**
     * @return createdDate
     */
    public function getCreatedDate() {
        return $this->createdDate;
    }

}
