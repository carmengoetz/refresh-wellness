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
 * @ORM\Table(name="Relationship")
 */

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;

class Relationship implements \Serializable
{
    /**
     *
     * @ORM\Column(type="string", length=100, unique=true)
     * @ORM\Id
     * @var mixed
     */
    private $relationshipId;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="relationshipsInitiated", fetch="EAGER")
     * @ORM\JoinColumn(name="user_one", referencedColumnName="user_id")
     * @var User
     */
    private $userIdOne;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="relationshipsRequested", fetch="EAGER")
     * @ORM\JoinColumn(name="user_two", referencedColumnName="user_id")
     * @var User
     */
    private $userIdTwo;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     *
     */
    private $dateStarted;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;
    /**
     *
     *Constructor
     */
    public function __construct()
    {

    }
    #region Getters and setters
    public function __get($name)
    {

    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function getRelationshipId(){
        return $this->relationshipId;
    }

    public function setRelationshipId(){
        $this->relationshipId = $this->userIdOne->userID . ':' . $this->userIdTwo->userID . ':' . $this->type;
    }


    public function getUserIdOne(){
        return $this->userIdOne;
    }

    public function setUserIdOne($userIdOne){
        $this->userIdOne = $userIdOne;
    }

    public function getUserIdTwo(){
        return $this->userIdTwo;
    }

    public function setUserIdTwo($userIdTwo){
        $this->userIdTwo = $userIdTwo;
    }

    public function getDateStarted(){
        return $this->dateStarted;
    }

    public function setDateStarted($dateStarted){
        $this->dateStarted = $dateStarted;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function getType(){
        return $this->type;
    }

    public function setType($type){
        $this->type = $type;
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