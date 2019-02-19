<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conversation
 *
 * @ORM\Table(name="Conversation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConversationRepository")
 */
class Conversation
{
    /**
     * @var int
     *
     * @ORM\Column(name="conversation_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="conversationUserOne")
     * @ORM\JoinColumn(name="user_one_id", referencedColumnName="user_id")
     * @var User
     */
    private $userOneID;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="conversationUserTwo")
     * @ORM\JoinColumn(name="user_two_id", referencedColumnName="user_id")
     * @var User
     */
    private $userTwoID;

    /**
     * @ORM\OneToOne(targetEntity="Message", inversedBy="conversationMessage")
     * @ORM\JoinColumn(name="last_message", referencedColumnName="message_id")
     * @var mixed
     */
    private $lastMessage;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userOneID
     */
    public function setUserOneID($userOneID)
    {
        $this->userOneID = $userOneID;

        return $this;
    }

    /**
     * Get userOneID
     */
    public function getUserOneID()
    {
        return $this->userOneID;
    }

    /**
     * Set userTwoID
     */
    public function setUserTwoID($userTwoID)
    {
        $this->userTwoID = $userTwoID;

        return $this;
    }

    /**
     * Get userTwoID
     */
    public function getUserTwoID()
    {
        return $this->userTwoID;
    }

    /**
     * Set lastMessage
     */
    public function setLastMessage($lastMessage)
    {
        $this->lastMessage = $lastMessage;

        return $this;
    }

    /**
     * Get lastMessage
     */
    public function getLastMessage()
    {
        return $this->lastMessage;
    }
}

