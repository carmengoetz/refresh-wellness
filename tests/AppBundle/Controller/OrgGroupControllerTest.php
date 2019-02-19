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
use AppBundle\DataFixtures\GroupOrgStatFixtures;
use Tests\AppBundle\DatabasePrimer;
/**
 * GroupControllerTest short summary.
 *
 * GroupControllerTest description.
 *
 * @version 1.0
 * @author cst245
 */
class OrgGroupControllerTest extends WebTestCase
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

        $fixture = new GroupOrgStatFixtures();
        $fixture->load($this->em);
        $fixture->loadGroup($this->em);
    }

    /**
     * Tests a regular user creating an organization whith all required fields
     **/
    public function testCreateOrgLoggedIn()
    {
        $groupToCreate = array(
            "groupName" => "I Hate Dogs Inc.",
            "groupDesc" => "We hate all dogs, including yours.",
            "groupType" => "organization",
            "groupAdmin" => "Draden Sawkey" );

        $url = "localhost/app_test.php/group/startgroup/" . urlencode(json_encode($groupToCreate));

        $username = 'grouporgcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        //look in database in Group table for the record that was just entered
        $group = $this->em->getRepository(Group::class);

        ////add an id that actually exists
        $this->record = $group->findOneBy(array('groupID' => '1'));

        $groups = $this->em->getRepository(Group::class)->findOneBy(array('groupName' => "I+Hate+Dogs+Inc."));

        $this->em->remove($groups);
        $this->em->flush();



        $this->assertTrue($response->body->status === 'success');
        //another way to check for success message
        //$this->assertEquals("success", $response->body->status);
    }


    /**
     * Tests a user trying to create a group while being logged out
     **/
    public function testCreateOrgLoggedOut()
    {
        \Httpful\Request::get("127.0.0.1:80/app_test.php/authenticate/logout")->send();


        $groupToCreate = array(
            "groupName" => "Gerbil Wheels",
            "groupDesc" => "Your gerbil goes faster in our wheels.",
            "groupType" => "organization",
            "groupAdmin" => "Abigail Williamson");

        $url = "localhost/app_test.php/group/startgroup/" . urlencode(json_encode($groupToCreate));

        $response = \Httpful\Request::get($url)->send();


        $this->assertTrue($response->code == 302);
        //another way to check for success message
        //$this->assertEquals("success", $response->body->status);
    }

    /**
     * Tests a regular user creating a group logged in, but the group type is not supported
     **/
    public function testCreateOrgWrongType()
    {
        $groupToCreate = array(
            "groupName" => "Tailless Kitten Rescues",
            "groupDesc" => "We only rescure cats with no tails.",
            "groupType" => "Not for Profit",
            "groupAdmin" => "Joe smith");

        $url = "localhost/app_test.php/group/startgroup/" . urlencode(json_encode($groupToCreate));

        $username = 'grouporgcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->message === 'group type must be standard or organization.');
        //another way to check for success message
        //$this->assertEquals("success", $response->body->status);
    }

    /**
     * Tests a regular user creating a group logged in, but the group type is left blank
     **/
    public function testCreateOrgNoGroupType()
    {
        $groupToCreate = array(
            "groupName" => "Chilly Chileans",
            "groupDesc" => "Cold people from south america who make chili.",
            "groupType" => "",
            "groupAdmin" => "Bob Dillon");

        $url = "localhost/app_test.php/group/startgroup/" . urlencode(json_encode($groupToCreate));

        $username = 'grouporgcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->message === 'group must have a group type. (either Standard or Organization).');
        //another way to check for success message
        //$this->assertEquals("success", $response->body->status);
    }

    /**
     * Tests a regular user creating a group logged in, but there is no admin set
     **/
    //public function testCreateOrgNoAdmin()
    //{
    //    $groupToCreate = array(
    //        "groupName" => "Cube Earth society",
    //        "groupDesc" => "Lego is square, checkmate athiests.",
    //        "groupType" => "organization",
    //        "groupAdmin" => "");

    //    $url = "localhost/group/startgroup/" . urlencode(json_encode($groupToCreate));

    //    $username = 'grouporgcontrollertest@yahoo.ca';
    //    $attemptedPassword = 'password';

    //    $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

    //    $this->assertTrue($response->body->status === 'failure');
    //    $this->assertTrue($response->body->message === 'Organizations must have an admin.');
    //    //another way to check for success message
    //    //$this->assertEquals("success", $response->body->status);
    //}

    /**
     * Tests a regular user creating a standard group logged in, but the group admin is blank
     **/
    public function testCreateGroupNoAdmin()
    {
        $groupToCreate = array(
            "groupName" => "Doofenshmirtz Evil Inc",
            "groupDesc" => "No platypi allowed.",
            "groupType" => "standard",
            "groupAdmin" => "");

        $url = "localhost/app_test.php/group/startgroup/" . urlencode(json_encode($groupToCreate));

        $username = 'grouporgcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        //look in database in Group table for the record that was just entered
        $group = $this->em->getRepository(Group::class);

        ////add an id that actually exists
        $this->record = $group->findOneBy(array('groupID' => '1'));

        $groups = $this->em->getRepository(Group::class)->findOneBy(array('groupName' => "Doofenshmirtz+Evil+Inc"));

        $this->em->remove($groups);
        $this->em->flush();


        $this->assertTrue($response->body->status === 'success');
        //another way to check for success message
        //$this->assertEquals("success", $response->body->status);
    }

    /**
     * deletes the created group and tears down the database connection
     */
    public function tearDown()
    {
        $fixture = new GroupOrgStatFixtures();
        $fixture->unloadGroup($this->em);
        $fixture->unload($this->em);

        if (!empty($this->groupToCreate))
        {
            $this->em->remove($this->groupToCreate[0]);
            $this->em->flush();
        }
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}
