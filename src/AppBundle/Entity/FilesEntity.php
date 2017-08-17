<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="files")
 */
class FilesEntity {

    /**
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="type", type="string", length=200)
     */
    private $type;

    /**
     * @ORM\Column(name="file_name", type="string", length=255)
     */
    private $fileName;

    /**
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @ORM\Column(name="status", type="smallint", length=1)
     */
    private $status = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * One File has One News
     *  @ORM\OneToMany(targetEntity="NewsEntity", mappedBy="file")
     */
    private $news;


    /**
     * @param [int]
     */
    public function setID($id)
    {
        $this->id = $id;
    }

    /**
     * @return id
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * @param [string]
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

     /**
     * @param [string]
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param [int]
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param [datetime]
     */
    public function setCreatedDate()
    {
        $this->createdDate = new \DateTime();
    }

    /**
     * @return createdDate
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }

    /**
     * @return file
     */
    public function file()
    {
        return $this->getWebPath() . $this->fileName;
    }

    /**
     * Called before saving the entity
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
//        if (null !== $this->file) {
//            // do whatever you want to generate a unique name
//            $filename = sha1(uniqid(mt_rand(), true));
//            $this->path = $filename.'.'.$this->file->guessExtension();
//        }
    }

    /**
     * Called before entity removal
     *
     * @ORM\PreRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

}
