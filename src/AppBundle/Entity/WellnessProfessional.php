<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * WellnessProfessional short summary.
 *
 * WellnessProfessional description.
 *
 * @ORM\Entity
 * @ORM\Table(name="WellnessProfessional")
 */
class WellnessProfessional
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $wellnessProfessionalID;

    public function getWellnessProfessionalID()
    {
        return $this->wellnessProfessionalID;
    }

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="wellnessProfessional")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * @var mixed
     */
    private $user;
    /**
     * Sets the user relationship
     * @param User $userID
     */
    public function setUser(User $userID)
    {
        $this->user = $userID;
    }

    public function getUserId()
    {
        return $this->user;
    }
    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank()
     * @assert\Length(
     *     min = 2,
     *     max = 80,
     *     minMessage = "Your practice name must be at least {{ limit }} characters long",
     *     maxMessage = "Your practice name cannot be longer than {{ limit }} characters")
     */
    private $practiceName;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @assert\Length(
     *     min = 5,
     *     max = 20,
     *     minMessage = "Your phone number must be at least {{ limit }} characters long",
     *     maxMessage = "Your phone number cannot be longer than {{ limit }} characters")
     */
    private $contactNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email()
     */
    private $contactEmail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * Summary of getPracticeName
     * @return mixed
     */
    public function getPracticeName(){
		return $this->practiceName;
	}

	/**
	 * Summary of setPracticeName
	 * @param mixed $practiceName
	 */
	public function setPracticeName($practiceName){
		$this->practiceName = $practiceName;
	}

	/**
	 * Summary of getContactNumber
	 * @return mixed
	 */
	public function getContactNumber(){
		return $this->contactNumber;
	}

	/**
	 * Summary of setContactNumber
	 * @param mixed $contactNumber
	 */
	public function setContactNumber($contactNumber){
		$this->contactNumber = $contactNumber;
	}

	/**
	 * Summary of getContactEmail
	 * @return mixed
	 */
	public function getContactEmail(){
		return $this->contactEmail;
	}

	/**
	 * Summary of setContactEmail
	 * @param mixed $contactEmail
	 */
	public function setContactEmail($contactEmail){
		$this->contactEmail = $contactEmail;
	}

	/**
	 * Summary of getWebsite
	 * @return mixed
	 */
	public function getWebsite(){
		return $this->website;
	}

	/**
	 * Summary of setWebsite
	 * @param mixed $website
	 */
	public function setWebsite($website){
		$this->website = $website;
	}

    /**
     * Summary of __construct
     */
    public function __construct()
    {

    }

}