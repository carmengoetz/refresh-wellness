<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Respondent short summary.
 *
 * Respondent description.
 *
 * @version 1.0
 * @author cst245
 * @ORM\Entity
 * @ORM\Table(name="Respondents")
 *
 */
class Respondent
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $respondentID;

    public function getRespondentID()
    {
        return $this->respondentID;
    }

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="respondent")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * @var mixed
     */
    private $user;

    public function setUser($user)
    {
        $this->user =$user ;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $atRisk;

    public function getAtRisk()
    {
        return $this->atRisk;
    }

    public function setAtRisk($atRisk)
    {
        $this->atRisk = $atRisk;
    }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $mentalState;

    public function getMentalState()
    {
        return $this->mentalState;
    }

    public function setMentalState($mentalState)
    {
        $this->mentalState = $mentalState;
    }

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stressLevel;

    public function getStressLevel()
    {
        return $this->stressLevel;
    }

    public function setStressLevel($stressLevel)
    {
        $this->stressLevel = $stressLevel;
    }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $emergencyContacts;

    public function getEmergencyContact()
    {
       return $this->emergencyContacts;
    }

    public function setEmergencyContact($emergencyContact)
    {
        $this->emergencyContacts = $emergencyContact;
    }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $doctor;

    public function getDoctor()
    {
        return $this->doctor;
    }

    public function setDoctor($doctor)
    {
        $this->doctor = $doctor;
    }

    /**
     * One respondent has many wellness records
     * @ORM\OneToMany(targetEntity="Wellness", mappedBy="respondent")
     */
    private $wellness;

    public function __construct(){
        $this->wellness = new ArrayCollection();
    }



}