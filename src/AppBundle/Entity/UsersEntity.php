<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;


/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UsersRepository")
 */
class UsersEntity {

    /**
     * @ORM\Column(type="integer", length=5)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="fullname", type="string", length=200)
     */
    private $fullname;

    /**
     * @ORM\Column(name="email", type="string", length=200)
     */
    private $email;

    /**
     * @ORM\Column(name="password", type="string", length=200)
     */
    private $password;

    /**
     * @ORM\Column(name="phone", type="string", length=15, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(name="gender", type="smallint", length=1)
     */
    private $gender = 0;

    /**
     * @ORM\Column(name="active_code", type="string", length=150, nullable=true)
     */
    private $activeCode;

    /**
     * @ORM\Column(name="status", type="smallint", length=1)
     */
    private $status = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="active_date", type="datetime", nullable=true)
     */
    private $activeDate;

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
     * @param $fullname
     */
    public function setFullname($fullname) {
        $this->fullname = $fullname;
    }

    /**
     * @return mixed
     */
    public function getFullname() {
        return $this->fullname;
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
     * @param $phone
     */
    public function setPhone($phone) {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * @param $address
     */
    public function setAddress($address) {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @param $gender
     */
    public function setGender($gender) {
        $this->gender = $gender;
    }

    /**
     * @return int
     */
    public function getGender() {
        return $this->gender;
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

    /**
     * @param $activeCode
     */
    public function setActiveCode($activeCode) {
        $this->activeCode = $activeCode;
    }

    /**
     * @return mixed
     */
    public function getActiveCode() {
        return $this->activeCode;
    }

    /**
     * @param $activeDate
     */
    public function setActiveDate($activeDate) {
        $this->activeDate = $activeDate;
    }

    /**
     * @return \DateTime
     */
    public function getActiveDate() {
        return $this->activeDate;
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
