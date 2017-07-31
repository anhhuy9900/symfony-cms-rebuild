<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;


/**
 * @ORM\Entity
 * @ORM\Table(name="system_roles")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Admin\AdminSystemRolesRepository")
 */
class SystemRolesEntity {

    /**
     * @ORM\Column(type="integer", length=5)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="role_name", type="string", length=200)
     */
    private $roleName;

    /**
     * @ORM\Column(name="role_type", type="text", nullable=true)
     */
    private $roleType;

    /**
     * @ORM\Column(name="role_status", type="smallint", length=1)
     */
    private $roleStatus = 0;

    /**
     * @ORM\Column(name="access", type="smallint", length=1)
     */
    private $access;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedDate", type="datetime", nullable=true)
     */
    private $updatedDate;

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
     * @param [string]
     */
    public function setRoleName($roleName) {
        $this->roleName = $roleName;
    }

    /**
     * @return roleName
     */
    public function getRoleName() {
        return $this->roleName;
    }

    /**
     * @param [string]
     */
    public function setRoleType($roleType) {
        $this->roleType = $roleType;
    }

    /**
     * @return roleType
     */
    public function getRoleType() {
        return $this->roleType;
    }

    /**
     * @param [int]
     */
    public function setRoleStatus($roleStatus) {
        $this->roleStatus = $roleStatus;
    }

    /**
     * @return roleStatus
     */
    public function getRoleStatus() {
        return $this->roleStatus;
    }

    /**
     * @param access
     */
    public function setAccess($access) {
        $this->access = $access;
    }

    /**
     * @return access
     */
    public function getAccess() {
        return $this->access;
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
