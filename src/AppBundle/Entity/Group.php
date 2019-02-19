<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Groups")
 */
class Group
{
    /**
     * @ORM\Column(type="string", length=40)
     * @ORM\Id
     */
    private $groupID;

    public function getgroupId()
    {
        return $this->groupID;
    }

    public function setgroupId($id)
    {
        $this->groupID = $id;
    }

    /**
     *
     * @ORM\OneToMany(targetEntity="GroupMember", mappedBy="group")
     * @var GroupMember[]|ArrayCollection
     */
    public $members;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 5,
     *     max = 80,
     *     minMessage = "Your group name must be at least {{ limit }} characters long",
     *     maxMessage = "Your group name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex(
     *     pattern = "/\d/",
     *     match = false,
     *     message = "Your group name cannot only contain a number")
     *
     * @Assert\Regex("/^\w+/")
     */
    private $groupName;

    public function getGroupName()
    {
        return $this->groupName;
    }

    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
    }

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @assert\Length(
     *     min = 12,
     *     max = 255,
     *     minMessage = "Your group description must be at least {{ limit }} characters long",
     *     maxMessage = "Your group description cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex(
     *     pattern = "/\d/",
     *     match = false,
     *     message = "Your group description cannot only contain a number")
     *
     * @Assert\Regex("/^\w+/")
     */
    private $groupDesc;

    public function getGroupDesc()
    {
        return $this->groupDesc;
    }

    public function setGroupDesc($gDesc)
    {
        $this->groupDesc = $gDesc;
    }

    /**
     * @ORM\Column(type="string", length=20,options={"default":"standard"})
     * @Assert\NotBlank()
     */
    private $groupType;

    public function getGroupType()
    {
        return $this->groupType;
    }

    public function setGroupType($gType)
    {
        $this->groupType = $gType;
    }


     /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="groupAdmin")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    private $groupAdmin;

    public function getGroupAdmin()
    {
        return $this->groupAdmin;
    }

    public function setGroupAdmin($user)
    {
        //$this->groupAdmin = $gAdmin;

        $this->groupAdmin = $user;
    }
}