<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;


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
     * @ORM\Column(name="category_id", type="integer", length=11)
     */
    private $categoryId = 0;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

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
    public function setCategoryID($categoryId) {
        $this->categoryId = $categoryId;
    }

    /**
     * @return categoryId
     */
    public function getCategoryID() {
        return $this->categoryId;
    }

    /**
     * @param [string]
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return title
     */
    public function getTitle() {
        return $this->title;
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
     * @param [string]
     */
    public function setImage($image) {
        $this->image = $image;
    }

    /**
     * @return image
     */
    public function getImage() {
        return $this->image;
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
    public function setUpdatedDate($updatedDate) {
        $this->updatedDate = $updatedDate;
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
