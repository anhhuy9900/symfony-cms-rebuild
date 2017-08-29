<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection,
    Doctrine\Common\Collections\ArrayCollection;

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
    private $access = 0;

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
     * @ORM\OneToMany(targetEntity="SystemUsersEntity", mappedBy="role")
     */
    protected $users;

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
     * @param $roleName
     */
    public function setRoleName($roleName) {
        $this->roleName = $roleName;
    }

    /**
     * @return mixed
     */
    public function getRoleName() {
        return $this->roleName;
    }

    /**
     * @param $roleType
     */
    public function setRoleType($roleType) {
        $this->roleType = $roleType;
    }

    /**
     * @return mixed
     */
    public function getRoleType() {
        return $this->roleType;
    }

    /**
     * @param $roleStatus
     */
    public function setRoleStatus($roleStatus) {
        $this->roleStatus = $roleStatus;
    }

    /**
     * @return int
     */
    public function getRoleStatus() {
        return $this->roleStatus;
    }

    /**
     * @param $access
     */
    public function setAccess($access) {
        $this->access = $access;
    }

    /**
     * @return int
     */
    public function getAccess() {
        return $this->access;
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
