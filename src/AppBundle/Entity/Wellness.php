<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 *
 * Wellness entity to be saved in the database of questions answered
 *
 * @ORM\Entity
 * @ORM\Table(name="Wellness")
 */
class Wellness
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Respondent", inversedBy="wellness", cascade={"persist"})
     * @ORM\JoinColumn(name="respondent_id", referencedColumnName="respondent_id")
     */
    private $respondent;

    public function setRespondent($respondent)
    {
        $this->respondent = $respondent;
    }

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 1,
     *      max = 10,
     *      minMessage = "Your mood is important to us, so please make sure it's between 1 and 10.",
     *      maxMessage = "Your mood is important to us, so please make sure it's between 1 and 10.",
     *      invalidMessage = "For us to properly serve you, we need you to answer the wellness questions correctly. Please ensure that you don't tamper with the 'mood' input.")
     */
    private $mood;

    public function getMood()
    {
        return $this->mood;
    }

    public function setMood($mood)
    {
        $this->mood = $mood;
    }

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 1,
     *      max = 10,
     *      minMessage = "Your energy is important to us, so please make sure it's between 1 and 10.",
     *      maxMessage = "Your energy is important to us, so please make sure it's between 1 and 10.",
     *      invalidMessage = "For us to properly serve you, we need you to answer the wellness questions correctly. Please ensure that you don't tamper with the 'energy' input.")
     *
     */
    private $energy;

    public function getEnergy()
    {
        return $this->energy;
    }

    public function setEnergy($energy)
    {
        $this->energy = $energy;
    }

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 1,
     *      max = 10,
     *      minMessage = "Your thoughts are important to us, so please make sure it's between 1 and 10.",
     *      maxMessage = "Your thoughts are important to us, so please make sure it's between 1 and 10.",
     *      invalidMessage = "For us to properly serve you, we need you to answer the wellness questions correctly. Please ensure that you don't tamper with the 'thoughts' input.")
     */
    private $thoughts;

    public function getThoughts()
    {
        return $this->thoughts;
    }

    public function setThoughts($thoughts)
    {
        $this->thoughts = $thoughts;
    }

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 1,
     *      max = 10,
     *      minMessage = "Your sleep is important to us, so please make sure it's between 1 and 10.",
     *      maxMessage = "Your sleep is important to us, so please make sure it's between 1 and 10.",
     *      invalidMessage = "For us to properly serve you, we need you to answer the wellness questions correctly. Please ensure that you don't tamper with the 'sleep' input.")
     */
    private $sleep;

    public function getSleep()
    {
        return $this->sleep;
    }

    public function setSleep($sleep)
    {
        $this->sleep = $sleep;
    }

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $date;

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getStats()
    {
        return array('mood'=>$this->mood, 'energy'=>$this->energy, 
            'thoughts'=>$this->thoughts, 'sleep'=>$this->sleep, 'date'=>$this->date );
    }
}