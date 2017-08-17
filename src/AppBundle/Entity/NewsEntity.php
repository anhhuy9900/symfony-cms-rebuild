<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\FilesEntity;


/**
 * @ORM\Entity
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NewsRepository")
 */
class NewsEntity {

    /**
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var CategoriesNewsEntity
     * @ORM\ManyToOne(targetEntity="CategoriesNewsEntity", inversedBy="news")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var FilesEntity
     * @ORM\ManyToOne(targetEntity="FilesEntity", inversedBy="news")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=true)
     */
    private $file;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(name="status", type="smallint", length=1)
     */
    private $status = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="datetime", nullable=true)
     */
    private $updatedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="TagsEntity", mappedBy="typeId")
     */
    protected $tags;

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
    public function setCategory($category) {
        $this->category = $category;
    }

    /**
     * @return category
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @param [string]
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param [string]
     */
    public function setSlug($slug) {
        $this->slug = $slug;
    }

    /**
     * @return slug
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @param $file
     * @return $this
     */
    public function setFile($file) {
        $this->file = $file;
        return $this;
    }

    /**
     * @return file
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * @param [string]
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return description
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param [string]
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * @return content
     */
    public function getContent() {
        return $this->content;
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
    public function setUpdatedDate() {
        $this->updatedDate = new \DateTime();
    }

    /**
     * @return updatedDate
     */
    public function getUpdatedDate() {
        return $this->updatedDate;
    }

    /**
     * @param [datetime]
     */
    public function setCreatedDate() {
        $this->createdDate = new \DateTime();
    }

    /**
     * @return createdDate
     */
    public function getCreatedDate() {
        return $this->createdDate;
    }

    /**
     *
     * @param \AppBundle\Entity\TagsEntity $tags
     *
     * @return tags
     */
    public function setTags(\AppBundle\Entity\TagsEntity $tags = null) {
        $this->tags = $tags;
    }

    /**
     * @return tags
     */
    public function getTags() {
        return $this->tags;
    }

}
