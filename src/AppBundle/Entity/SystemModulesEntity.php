<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;


/**
 * @ORM\Entity
 * @ORM\Table(name="system_modules")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Admin\AdminSystemModulesRepository")
 */
class SystemModulesEntity {

    /**
     * @ORM\Column(type="integer", length=5)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="parent_id", type="integer", length=5)
     */
    private $parentId = 0;

    /**
     * @ORM\Column(name="module_name", type="string", length=200, nullable=true)
     */
    private $moduleName;

    /**
     * @ORM\Column(name="module_alias", type="string", length=200, nullable=true)
     */
    private $moduleAlias;

    /**
     * @ORM\Column(name="module_type", type="string", length=255, nullable=true)
     */
    private $moduleType;

    /**
     * @ORM\Column(name="module_permission", type="smallint", length=1)
     */
    private $modulePermission = 0;

    /**
     * @ORM\Column(name="module_status", type="smallint", length=1)
     */
    private $moduleStatus = 0;

    /**
     * @ORM\Column(name="module_order", type="integer", length=10)
     */
    private $moduleOrder = 0;

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
     * @param $id
     */
    public function setID($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getID() {
        return $this->id;
    }

    /**
     * @param $parentId
     */
    public function setParentID($parentId) {
        $this->parentId = $parentId;
    }

    /**
     * @return int
     */
    public function getParentID() {
        return $this->parentId;
    }

    /**
     * @param $moduleName
     */
    public function setModuleName($moduleName) {
        $this->moduleName = $moduleName;
    }

    /**
     * @return mixed
     */
    public function getModuleName() {
        return $this->moduleName;
    }

    /**
     * @param $moduleAlias
     */
    public function setModuleAlias($moduleAlias) {
        $this->moduleAlias = $moduleAlias;
    }

    /**
     * @return mixed
     */
    public function getModuleAlias() {
        return $this->moduleAlias;
    }

    /**
     * @param $moduleType
     */
    public function setModuleType($moduleType) {
        $this->moduleType = $moduleType;
    }

    /**
     * @return mixed
     */
    public function getModuleType() {
        return $this->moduleType;
    }

    /**
     * @param $modulePermission
     */
    public function setModulePermission($modulePermission) {
        $this->modulePermission = $modulePermission;
    }

    /**
     * @return modulePermission
     */
    public function getModulePermission() {
        return $this->modulePermission;
    }

    /**
     * @param $moduleStatus
     */
    public function setModuleStatus($moduleStatus) {
        $this->moduleStatus = $moduleStatus;
    }

    /**
     * @return int
     */
    public function getModuleStatus() {
        return $this->moduleStatus;
    }

    /**
     * @param $moduleOrder
     */
    public function setModuleOrder($moduleOrder) {
        $this->moduleOrder = $moduleOrder;
    }

    /**
     * @return int
     */
    public function getModuleOrder() {
        return $this->moduleOrder;
    }


    public function setUpdatedDate() {
        $this->updatedDate = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedDate() {
        return $this->updatedDate;
    }


    public function setCreatedDate() {
        $this->createdDate = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate() {
        return $this->createdDate;
    }

}
