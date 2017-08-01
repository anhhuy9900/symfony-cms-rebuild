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
    public function setParentID($parentId) {
        $this->parentId = $parentId;
    }

    /**
     * @return parentId
     */
    public function getParentID() {
        return $this->parentId;
    }

    /**
     * @param [string]
     */
    public function setModuleName($moduleName) {
        $this->moduleName = $moduleName;
    }

    /**
     * @return moduleName
     */
    public function getModuleName() {
        return $this->moduleName;
    }

    /**
     * @param [string]
     */
    public function setModuleAlias($moduleAlias) {
        $this->moduleAlias = $moduleAlias;
    }

    /**
     * @return moduleAlias
     */
    public function getModuleAlias() {
        return $this->moduleAlias;
    }

    /**
     * @param [string]
     */
    public function setModuleType($moduleType) {
        $this->moduleType = $moduleType;
    }

    /**
     * @return moduleType
     */
    public function getModuleType() {
        return $this->moduleType;
    }

    /**
     * @param [int]
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
     * @param [int]
     */
    public function setModuleStatus($moduleStatus) {
        $this->moduleStatus = $moduleStatus;
    }

    /**
     * @return moduleStatus
     */
    public function getModuleStatus() {
        return $this->moduleStatus;
    }

    /**
     * @param [int]
     */
    public function setModuleOrder($moduleOrder) {
        $this->moduleOrder = $moduleOrder;
    }

    /**
     * @return moduleOrder
     */
    public function getModuleOrder() {
        return $this->moduleOrder;
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
