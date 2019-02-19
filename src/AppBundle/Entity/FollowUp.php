<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="FollowUp")
 */
class FollowUp
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
     * @ORM\Column(type="integer")
     */
    private $respondentId;

    public function getRespondentId()
    {
        return $this->respondentId;
    }

    public function setRespondentId($respondentId)
    {
        $this->respondentId = $respondentId;
    }

    /**
     * @ORM\Column(type="string")
     */
    private $question;

    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @ORM\Column(type="string")
     */
    private $response;

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @ORM\Column(type="string")
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

}