<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Message entity to be saved in the database with information about messages sent between users.
 *
 * @version 1.0
 * @author cst231
 *
 * @ORM\Entity
 * @ORM\Table(name="Message")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $messageID;

    public function getMessageID()
    {
        return $this->messageID;
    }

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="message", cascade={"persist"})
     * @ORM\JoinColumn(name="sender", referencedColumnName="user_id")
     * @var mixed
     */
    private $sender;

    public function setSender($user)
    {
        $this->sender = $user;
    }

    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="message", cascade={"persist"})
     * @ORM\JoinColumn(name="receiver", referencedColumnName="user_id")
     * @var mixed
     */
    private $receiver;

    public function setReceiver($user)
    {
        $this->receiver = $user;
    }

    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     * message = "Cannot send blank message.")
     * @Assert\Length(
     *  min = 1,
     *  max = 1500,
     *  minMessage = "Cannot send blank message.",
     *  maxMessage = "Message must be less than 1500 characters.")
     *
     */
    private $messageContent;

    public function getMessageContent()
    {
        return $this->messageContent;
    }

    public function setMessageContent($message)
    {
        $this->messageContent = $message;
    }

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $dateSent;

    public function setDateSent($date)
    {
        $this->dateSent = $date;
    }

    public function getDateSent()
    {
        return $this->dateSent;
    }

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank()
     * @Assert\Time()
     */
    private $timeSent;

    public function setTimeSent($time)
    {
        $this->timeSent = $time;
    }

    public function getTimeSent()
    {
        return $this->timeSent;
    }

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRead;

    public function getIsRead()
    {
        return $this->isRead;
    }

    public function setIsRead($read = true)
    {
        $this->isRead = $read;
    }

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    public function setIsDeleted($deleted = true)
    {
        $this->isDeleted = $deleted;
    }

    public function __construct()
    {
        $this->dateSent = new \DateTime();
        $this->timeSent = $this->dateSent;
        $this->isRead = false;
        $this->isDeleted = false;
    }

    /**
     * @ORM\OneToOne(targetEntity="Conversation", mappedBy="lastMessage", cascade={"persist"})
     * @var mixed
     */
    private $conversationMessage;

}