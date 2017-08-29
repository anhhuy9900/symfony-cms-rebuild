<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;


/**
 * @ORM\Entity
 * @ORM\Table(name="system_users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Admin\AdminSystemUsersRepository")
 */
class SystemUsersEntity {

    /**
     * @ORM\Column(type="integer", length=5)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var SystemRolesEntity
     * @ORM\ManyToOne(targetEntity="SystemRolesEntity", inversedBy="users")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    private $role;

    /**
     * @ORM\Column(name="username", type="string", length=150)
     */
    private $username;

    /**
     * @ORM\Column(name="email", type="string", length=150)
     */
    private $email;

    /**
     * @ORM\Column(name="password", type="string", length=200)
     */
    private $password;

    /**
     * @ORM\Column(name="user_token", type="string", length=60, nullable=true)
     */
    private $userToken;

    /**
     * @ORM\Column(name="permission_limit", type="smallint", length=1)
     */
    private $permissionLimit = 0;

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
     * @param SystemRolesEntity|null $role
     */
    public function setRole(\AppBundle\Entity\SystemRolesEntity $role = null) {
        $this->role = $role;
    }

    /**
     * @return SystemRolesEntity
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * @param $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param $userToken
     */
    public function setUserToken($userToken) {
        $this->userToken = $userToken;
    }

    /**
     * @return mixed
     */
    public function getUserToken() {
        return $this->userToken;
    }

    /**
     * @param $permissionLimit
     */
    public function setPermissionLimit($permissionLimit) {
        $this->permissionLimit = $permissionLimit;
    }

    /**
     * @return int
     */
    public function getPermissionLimit() {
        return $this->permissionLimit;
    }

    /**
     * @param $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStatus() {
        return $this->status;
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
