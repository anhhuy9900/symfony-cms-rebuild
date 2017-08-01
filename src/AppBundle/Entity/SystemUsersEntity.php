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
     * @ORM\Column(name="role_id", type="integer", length=5)
     */
    private $roleId;

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
    public function setRoleID($roleId) {
        $this->roleId = $roleId;
    }

    /**
     * @return roleId
     */
    public function getRoleID() {
        return $this->roleId;
    }

    /**
     * @param [string]
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return username
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param [string]
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return email
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param [string]
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return password
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param  [string]
     */
    public function setUserToken($userToken) {
        $this->userToken = $userToken;
    }

    /**
     * @return userToken
     */
    public function getUserToken() {
        return $this->userToken;
    }

    /**
     * @param  [int]
     */
    public function setPermissionLimit($permissionLimit) {
        $this->permissionLimit = $permissionLimit;
    }

    /**
     * @return permissionLimit
     */
    public function getPermissionLimit() {
        return $this->permissionLimit;
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
