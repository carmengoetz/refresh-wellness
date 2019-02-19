<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Services\getCountFromDB;

/**
 * User entity to be saved in the database
 *
 * @version 1.0
 * @author cst213
 * @ORM\Entity
 * @ORM\Table(name="Users")
 * @UniqueEntity(fields="email", message="That email address is already registered") 
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 *
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="string", length=40)
     * @ORM\Id
     */
    private $userID;

    /**
     * @ORM\OneToOne(targetEntity="Respondent", mappedBy="user", cascade={"persist"})
     * @var mixed
     */
    private $respondent;

    /**
     * @ORM\OneToOne(targetEntity="WellnessProfessional", mappedBy="user")
     * @var mixed
     */
    private $wellnessProfessional;

    /**
     * Getter for related Wellness Pro object
     * @return mixed
     */
    public function getWellnessProfessional()
    {
        return $this->wellnessProfessional;
    }

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank()
     * @assert\Length(
     *     min = 2,
     *     max = 80,
     *     minMessage = "Your first name must be at least {{ limit }} characters long",
     *     maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank()
     * @assert\Length(
     *     min = 2,
     *     max = 80,
     *     minMessage = "Your last name must be at least {{ limit }} characters long",
     *     maxMessage = "Your last name cannot be longer than {{ limit }} characters"
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @var mixed
     */
    private $email;

    /**

     * @Assert\Length(max=4096)
     * @Assert\NotBlank()
     * @var mixed
     */
    private $plainPassword;

    /**
     *
     * @ORM\Column(type="string", length=64)

     */
    private $password;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     * @Assert\Date()
     * @Assert\LessThan("-13 years", message = "You need to be at least 13 years old to register")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\NotBlank()
     * @Assert\Country()
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank()
     * @assert\Length(
     *     min = 2,
     *     max = 80,
     *     minMessage = "Your city must be at least {{ limit }} characters long",
     *     maxMessage = "Your city cannot be longer than {{ limit }} characters"
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="integer",options={"default":0})
     * @Assert\NotBlank()
     */
    private $numFriends;
    /**
     * @ORM\Column(type="integer",options={"default":0})
     * @Assert\NotBlank()
     */
    private $numSupporters;
    /**
     * @ORM\Column(type="integer",options={"default":0})
     * @Assert\NotBlank()
     */
    private $numSupportees;

    /**
     * @ORM\Column(type="string", length=20,options={"default":"active"})
     * @Assert\NotBlank()
     */
    private $status;

    /**
     *
     * @ORM\OneToMany(targetEntity="Relationship", mappedBy="userIdOne")
     * @var Relationship[]|ArrayCollection
     */
    public $relationshipsInitiated;

    /**
     *
     * @ORM\OneToMany(targetEntity="Conversation", mappedBy="userOneID")
     * @var Conversation[]|ArrayCollection
     */
    public $conversationUserOne;

    /**
     *
     * @ORM\OneToMany(targetEntity="Conversation", mappedBy="userTwoID")
     * @var Conversation[]|ArrayCollection
     */
    public $conversationUserTwo;

    /**
     *
     * @ORM\OneToMany(targetEntity="Relationship", mappedBy="userIdTwo")
     * @var Relationship[]|ArrayCollection
     */
    public $relationshipsRequested;

    /**
     *  getter for relationships
     * @return Relationship[]|Doctrine\Common\Collections\ArrayCollection
     */
    public function getRelationshipsInitiated()
    {


       return  $this->relationshipsInitiated;
    }


    /**
     *
     * @ORM\OneToMany(targetEntity="GroupMember", mappedBy="user")
     * @var GroupMember[]|ArrayCollection
     */
    public $groupsJoined;

    /**
     *
     * @ORM\Column(type="boolean")
     *
     */
    private $isActive;


    private $roles;

    /**
     * One user can have many messages sent
     * @ORM\OneToMany(targetEntity="Message", mappedBy="sender")
     * @var Message[]|ArrayCollection
     */
    private $messageSender;

    /**
     * One user can have many messages received
     * @ORM\OneToMany(targetEntity="Message", mappedBy="receiver")
     * @var Message[]|ArrayCollection
     */
    private $messageReceiver;

    /**
     *
     * Constructor for the User class

     */
    public function __construct()
    {
        $this->relationshipsInitiated = new \Doctrine\Common\Collections\ArrayCollection();
        $this->relationshipsRequested = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setNumFriends(0);
        $this->setNumSupportees(0);
        $this->setNumSupporters(0);
        $this->setStatus('active');
        $this->isActive = true;
        $this->birthDate = new \DateTime();
    }

    /**
     * genereic getter
     * @param mixed $name
     * @return mixed
     */
    public function __get($name)
    {
        if ( isset($this->$name))
        {
            return $this->$name;
        }
        else
        {
            return $name;
        }
    }

    /**
     * genereic setter
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->$name = $value;

    }


    /**
     * setter for birthdate
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->birthDate = $date;

    }

    /**
     * getter for email
     * @return string
     */
    public function getEmail()
    {
        return $this->getUsername();
    }

    /**
     * setter for email
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setCountry($cuntry)
    {
        $this->country = $cuntry;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function equals($user){
        if(strcmp($this->firstName,$user->firstName) == 0){
            return true;
        }else{
            return false;
        }
    }

    #endregion

    /**
     * setter for id (GUID)
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userID = $userId;
    }

    /**
     * getter for id (GUID)
     */
    public function getUserId()
    {
        return $this->userID;
    }


    /**
     * getter for name
     * @return string
     */
    public function getName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function setFirstName($fname)
    {
        $this->firstName = $fname;
    }

    public function setLastName($lname)
    {
        $this->lastName = $lname;
    }

    /**
     * setter for username (email)
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->email = $username;
    }

    /**
     * getter for plain password
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * setter for plain password
     * @param mixed $password
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * setter for id (GUID)
     * @param mixed $userId
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * getter for id (GUID)
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Setter for isActive
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * setter for password
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    /**
     * Getter for numFriends
     * @return \double|integer
     */
    public function getNumFriends()
    {
        return $this->numFriends;
    }
    /**
     * increments the number of friends by one
     * @return boolean
     */
    public function incrementFriends()
    {
        if ($this->numFriends == 100)
        {
            return false;
        }
        else
        {
            $this->numFriends++;
            return true;
        }
    }
    /**
     * Decrements the number of friends by one
     * @return boolean
     */
    public function decrementFriends()
    {

        if ($this->numFriends <= 0)
        {
            $this->numFriends = 0;
            return false;
        }
        else
        {
            $this->numFriends--;
            return true;
        }
    }
    /**
     * Setter for numFriends
     * @param mixed $numFriends
     * @return boolean
     */
    public function setNumFriends($numFriends)
    {
        if ( $numFriends <= 100 && $numFriends >= 0 )
        {
            $this->numFriends = $numFriends;
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Getter for numFriends
     * @return \double|integer
     */
    public function getNumSupporters()
    {
        return $this->numSupporters;
    }
    /**
     * Increment numSupporters
     * @return boolean
     */
    public function incrementSupporters()
    {
        $this->numSupporters++;

        if ($this->numSupporters > 5)
        {
            $this->numSupporters = 5;
            return false;
        }
        else
        {
            return true;
        }
    }
    /**
     * decrement supporters by one
     * @return boolean
     */
    public function decrementSupporters()
    {
        $this->numSupporters--;
        if ($this->numSupporters < 0)
        {
            $this->numSupporters = 0;
            return false;
        }
        else
        {
            return true;
        }
    }
    /**
     * setter for numSupporters
     * @param mixed $numFriends
     * @return boolean
     */
    public function setNumSupporters($numFriends)
    {
        if ( $numFriends <= 5 && $numFriends >= 0 )
        {
            $this->numSupporters = $numFriends;
            return true;
        }
        else{
            return false;
        }
    }
    /**
     * getter for numFriends
     * @return \double|integer
     */
    public function getNumSupportees()
    {
        return $this->numSupportees;
    }
    /**
     * increment supportees by one
     * @return boolean
     */
    public function incrementSupportees()
    {
        $this->numSupportees++;

        if ($this->numSupportees > 5)
        {
            $this->numSupportees = 5;
            return false;
        }
        else
        {
            return true;
        }
    }
    /**
     * decrement supportees by one
     * @return boolean
     */
    public function decrementSupportees()
    {
        $this->numSupportees--;
        if ($this->numSupportees < 0)
        {
            $this->numSupportees = 0;
            return false;
        }
        else
        {
            return true;
        }
    }
    /**
     * setter for numSupportees
     * @param mixed $numSupportees
     * @return boolean
     */
    public function setNumSupportees($numSupportees)
    {
        if ( $numSupportees <= 5 && $numSupportees >= 0 )
        {
            $this->numSupportees = $numSupportees;
            return true;
        }
        else{
            return false;
        }
    }

    //getter is a service.
    //public function getNumWellnessProf()
    //{
    //    //variable to return

    //    return 0;
    //}

    #region Symfony\Component\Security\Core\User\UserInterface Members

    /**
     * Returns the roles granted to the user.
     * <code>
     * public function getRoles()
     * {
     * return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return  (Role|string)[] The user roles
     */
    public function getRoles()
    {
        // TODO: implement the function Symfony\Component\Security\Core\User\UserInterface::getRoles
        return array('ROLE_USER');
    }

    /**
     * Returns the password used to authenticate the user.
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    function getPassword()
    {
        // TODO: implement the function Symfony\Component\Security\Core\User\UserInterface::getPassword
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    function getSalt()
    {
        // TODO: implement the function Symfony\Component\Security\Core\User\UserInterface::getSalt
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    function getUsername()
    {
        // TODO: implement the function Symfony\Component\Security\Core\User\UserInterface::getUsername
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    function eraseCredentials()
    {
        // TODO: implement the function Symfony\Component\Security\Core\User\UserInterface::eraseCredentials
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

        return serialize(array(
                $this->userID,
                $this->email,
                $this->password
            ));
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
        list (
            $this->userID,
            $this->email,
            $this->password
            ) = unserialize($serialized);
    }

    #endregion
}