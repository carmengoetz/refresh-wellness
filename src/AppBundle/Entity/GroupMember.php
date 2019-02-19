<?php

namespace AppBundle\Entity;

/**
 * User short summary.
 *
 * User description.
 *
 * @version 1.0
 * @author cst236
 * @ORM\Entity
 * @ORM\Table(name="GroupMember")
 */

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;

class GroupMember implements \Serializable
{
    /**
     *
     * @ORM\Column(type="string", length=100, unique=true)
     * @ORM\Id
     * @var mixed
     */
    private $groupMemberId;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="groupsJoined")
     * @ORM\JoinColumn(name="user", referencedColumnName="user_id")
     * @var User
     */
    private $user;


    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="member")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="group_id")
     * @var Group
     */
    private $group;


    /**
     * @ORM\Column(type="date", nullable=true)
     *
     *
     */
    private $dateJoined;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $groupRole;
    /**
     *
     *Constructor
     */
    public function __construct()
    {

    }
    #region Getters and setters
    /**
     * Getter for groupMemberId
     * @return mixed
     */
    public function getGroupMemberId(){
		return $this->groupMemberId;
	}
    /**
     * Setter for groupMemberId
     * @return mixed
     */
	public function setGroupMemberId(){
		$this->groupMemberId = $this->group->getgroupId() . ":" . $this->user->getUserId();
	}
    /**
     * Getter for user
     * @return mixed
     */
	public function getUser(){
		return $this->user;
	}
    /**
     * setter for user
     * @return mixed
     */
	public function setUser($user){
		$this->user = $user;
	}
    /**
     * Getter for group
     * @return mixed
     */
	public function getGroup(){
		return $this->group;
	}
    /**
     * Setter for group
     * @return mixed
     */
	public function setGroup($group){
		$this->group = $group;
	}
    /**
     * Getter for dateJoined
     * @return mixed
     */
	public function getDateJoined(){
		return $this->dateJoined;
	}
    /**
     * Setter for dateJoined
     * @return mixed
     */
	public function setDateJoined($dateJoined){
		$this->dateJoined = $dateJoined;
	}
    /**
     * Getter for status
     * @return mixed
     */
	public function getStatus(){
		return $this->status;
	}
    /**
     * Setter for status
     * @return mixed
     */
	public function setStatus($status){
		$this->status = $status;
	}
    /**
     * Getter for groupRole
     * @return mixed
     */
	public function getGroupRole(){
		return $this->groupRole;
	}
    /**
     * Setter for groupRole
     * @return mixed
     */
	public function setGroupRole($groupRole){
		$this->groupRole = $groupRole;
	}
    #endregion

    #region Serializable Members

    /**
     * String representation of object
     * Should return the string representation of the object.
     *
     * @return string
     */
    function serialize()
    {
        // TODO: implement the function Serializable::serialize
    }

    /**
     * Constructs the object
     * Called during unserialization of the object.
     *
     * @param string $serialized The string representation of the object.
     *
     * @return void
     */
    function unserialize($serialized)
    {
        // TODO: implement the function Serializable::unserialize
    }

    #endregion
}