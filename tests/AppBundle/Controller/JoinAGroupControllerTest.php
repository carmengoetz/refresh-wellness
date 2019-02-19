<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\TestCase;
use AppBundle\DataFixtures\RelationshipFixtures;
use AppBundle\Entity\User;
use AppBundle\Entity\Group;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\DataFixtures\GroupStatFixtures;
use AppBundle\DataFixtures\JoinAGroupFixtures;
use Tests\AppBundle\DatabasePrimer;
/**
 * GroupControllerTest short summary.
 *
 * GroupControllerTest description.
 *
 * @version 1.0
 * @author cst245
 */
class JoinAGroupControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     * @var mixed
     */
    private $em;
    private $record;

    //group to create from database
    private $groupToCreate;

    public function setUp()
    {
        self::bootKernel();

        //Apply the primer
        DatabasePrimer::prime(self::$kernel);

        //Set entity manager
        $this->em = DatabasePrimer::$entityManager;

        $fixture = new JoinAGroupFixtures();
        $fixture->load($this->em);

    }

    /**
     * deletes the created group and tears down the database connection
     */
    public function tearDown()
    {
        $fixture = new JoinAGroupFixtures();

        $fixture->unload($this->em);


        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }

    public function testJoinAGroup()
    {
        $url = "http://127.0.0.1:80/app_test.php/group/join/GR4Joining1";

        $username = "joingroupsuccess@email.com";
        $password = "password";

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertTrue($response->body->status === 'success');
        $this->assertTrue($response->body->data->groupID === "GR4Joining1");
        $this->assertTrue($response->body->data->groupName === "Legs For Days");
        $this->assertTrue($response->body->data->memberID === "GR4Joining1:U4JoiningGR");

        //Get the user from DB
        $user1 = $this->em->getRepository(User::class)
           ->findOneBy(array('userID' => 'U4JoiningGR'));

        $this->em->refresh($user1);

        $this->assertTrue(!empty($user1));

        $found = false;
        //Loop through all the groups they've joined and look for the one matching the group
        foreach ($user1->groupsJoined as $group)
        {
        	if ( $group->getGroup()->getgroupId() === 'GR4Joining1' )
            {
                $found = true;
            }
        }

        $this->assertTrue($found);

        //Get the group
        $group1 = $this->em->getRepository(Group::class)
           ->findOneBy(array('groupID' => 'GR4Joining1'));
        $this->em->refresh($group1);
        $this->assertTrue(!empty($group1));
        $found = false;
        //Loop through its members and look for the one matching the user
        foreach ($group1->members as $member)
        {
        	if ( $member->getUser()->getUserId() === 'U4JoiningGR')
            {
                $found = true;
            }
        }

        $this->assertTrue($found);

    }

    public function testJoinAGroupAlreadyAMember()
    {
        $url = "http://127.0.0.1:80/app_test.php/group/join/GR4Joining1";

        $username = "joingroupalready@email.com";
        $password = "password";

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->message === "You are already a member of the group Legs For Days.");

    }

    public function testJoinAGroupNotLoggedIn()
    {
        $url = "http://127.0.0.1:80/app_test.php/group/join/GR4Joining1";



        $response = \Httpful\Request::get($url)->send();

        $this->assertTrue($response->code == 302);
    }

    public function testJoinAGroupDoesntExist()
    {
        $url = "http://127.0.0.1:80/app_test.php/group/join/GRDontExist";

        $username = "joingroupsuccess@email.com";
        $password = "password";

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->message === "Group not found.");
    }

    public function testJoinAGroupAlreadyAdminOf()
    {
        $url = "http://127.0.0.1:80/app_test.php/group/join/ORG4Joining2";

        $username = "joingroupadmin@email.com";
        $password = "password";

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->message === "You are already a member of the group Legs For Weeks.");
    }

}