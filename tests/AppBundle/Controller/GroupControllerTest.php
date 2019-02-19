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
use AppBundle\DataFixtures\GroupStatFixtures;
use Tests\AppBundle\DatabasePrimer;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * GroupControllerTest short summary.
 *
 * GroupControllerTest description.
 *
 * @version 1.0
 * @author cst245
 */
class GroupControllerTest extends WebTestCase
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

        $fixture = new GroupStatFixtures();
        $fixture->load($this->em);
        $fixture->loadGroup($this->em);
    }

    /**
     * test if group name is good
     **/
    public function testAddGroupValidGroupName()
    {

        $groupToCreate = array(
            "groupName" => "Family and Friends",
            "groupDesc" => "This is a group for my friends and family",
            "groupType" => "standard");

        $url = "localhost/app_test.php/group/startgroup/" . urlencode(json_encode($groupToCreate));

        $username = 'groupcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        //look in database in Group table for the record that was just entered
        $group = $this->em->getRepository(Group::class);

        ////add an id that actually exists
        $this->record = $group->findOneBy(array('groupID' => '1'));

        $groups = $this->em->getRepository(Group::class)->findOneBy(array('groupName' => "Family+and+Friends"));



        $this->assertTrue($response->body->status === 'success');
        //another way to check for success message
        //$this->assertEquals("success", $response->body->status);
    }
    /**
     *  test if group name is too short -- not minimum 5 characters
     */
    public function testInvalidGroupNameTooShort()
    {
        $groupToCreate = array(
            "groupName" => "blep",
            "groupDesc" => "This is a group for my friends and family",
            "groupType" => "standard");

        $url = "localhost/app_test.php/group/startgroup/". urlencode(json_encode($groupToCreate));
        $username = 'groupcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        //look in database in Group table for the record with the attempted values
        $this->em->getRepository(Group::class);

        //make sure the group object was not created
        $this->assertEquals(null,$this->record);
    }

    /**
     * test if group name is too long -- more than 80 characters
     */
    public function testInvalidGroupNameTooLong()
    {
       $groupToCreate = array(
            "groupName" => "Supercalifragilis-ticexpialidocious! Even though the sound of it is something quite atrocious",
            "groupDesc" => "This is a group for my friends and family",
            "groupType" => "standard");

       $url = "localhost/app_test.php/group/startgroup/". urlencode(json_encode($groupToCreate));
        $username = 'groupcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->em->getRepository(Group::class);

        //Assert that there is no data found
        //$this->assertTrue(empty($response->body->data));
        $this->assertEquals(null,$this->record);
    }

    /**
     * test if group name is empty
     */
    public function testEmptyGroupName()
    {
        $groupToCreate = array(
            "groupName" => "",
            "groupDesc" => "This is a group for my friends and family",
            "groupType" => "standard");

        $url = "localhost/app_test.php/group/startgroup/". urlencode(json_encode($groupToCreate));
        $username = 'groupcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->em->getRepository(Group::class);

        //Assert that there is no data found
        //$this->assertTrue(empty($response->body->data));
        $this->assertEquals(null,$this->record);
    }

    /**
     * Test that the group name doesn't start with a special character
     */
    public function testGroupNameSpecialCharacters()
    {
        $groupToCreate = array(
            "groupName" => "%mygroup%",
            "groupDesc" => "This is a group for my friends and family",
            "groupType" => "standard");

        $url = "localhost/app_test.php/group/startgroup/". urlencode(json_encode($groupToCreate));
        $username = 'groupcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->em->getRepository(Group::class);

        //Assert that there is no data found
        //$this->assertTrue(empty($response->body->data));
        $this->assertEquals(null,$this->record);
    }

    /**
     * test that the group name isn't just all spaces
     */
    public function testGroupNameJustSpaces()
    {
        $groupToCreate = array(
            "groupName" => "      ",
            "groupDesc" => "This is a group for my friends and family",
            "groupType" => "standard");

        $url = "localhost/app_test.php/group/startgroup/". urlencode(json_encode($groupToCreate));
        $username = 'groupcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->em->getRepository(Group::class);

        //Assert that there is no data found
        //$this->assertTrue(empty($response->body->data));
        $this->assertEquals(null,$this->record);
    }

    /**
     * test that the group desc isn't empty with a valid group name
     */
    public function testAddGroupEmptyDesc()
    {
        $groupToCreate = array(
            "groupName" => "My New Cool Group",
            "groupDesc" => '',
            "groupType" => "standard");

        $url = "localhost/app_test.php/group/startgroup/". urlencode(json_encode($groupToCreate));
        $username = 'groupcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->em->getRepository(Group::class);

        //Assert that there is no data found
        //$this->assertTrue(empty($response->body->data));
        $this->assertEquals(null, $this->record);
    }

    /**
     * test that the group desc isn't just spaces with a valid group name
     */
    public function testAddGroupDescJustSpaces()
    {
        $groupToCreate = array(
            "groupName" => "My New Cool Group",
            "groupDesc" => '      ',
            "groupType" => "standard");

        $url = "localhost/app_test.php/group/startgroup/". urlencode(json_encode($groupToCreate));
        $username = 'groupcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->em->getRepository(Group::class);

        //Assert that there is no data found
        //$this->assertTrue(empty($response->body->data));
        $this->assertEquals(null, $this->record);
    }

    /**
     * test that the group desc isn't just all numbers with a valid group name
     */
    public function testAddGroupDescJustNumbers()
    {
        $groupToCreate = array(
            "groupName" => "My New Cool Group",
            "groupDesc" => '0110100001100101 0110001101101011',
            "groupType" => "standard");

        $url = "localhost/app_test.php/group/startgroup/". urlencode(json_encode($groupToCreate));
        $username = 'groupcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->em->getRepository(Group::class);

        //Assert that there is no data found
        //$this->assertTrue(empty($response->body->data));
        $this->assertEquals(null, $this->record);

    }

    /**
     * test that the group desc doesn't start with a special character with a valid group name
     */
    public function testAddGroupDescSpecialChar()
    {
        $groupToCreate = array(
            "groupName" => "My New Cool Group",
            "groupDesc" => '$$$$$moneymoney',
            "groupType" => "standard");

        $url = "localhost/app_test.php/group/startgroup/" . urlencode(json_encode($groupToCreate));
        $username = 'groupcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->em->getRepository(Group::class);

        //Assert that there is no data found
        //$this->assertTrue(empty($response->body->data));
        $this->assertEquals(null,$this->record);
    }

    /**
     * test that the group desc isn't too short -- minimum 12 characters long
     * with a valid group name
     */
    public function testAddGroupDescTooShort()
    {
        $groupToCreate = array(
            "groupName" => "My New Cool Group",
            "groupDesc" => 'This is',
            "groupType" => "standard");

        $url = "localhost/app_test.php/group/startgroup/" . urlencode(json_encode($groupToCreate));
        $username = 'groupcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->em->getRepository(Group::class);

        //Assert that there is no data found
        //$this->assertTrue(empty($response->body->data));
        $this->assertEquals(null, $this->record);
    }

    /**
     * test that the group desc isn't too long -- maximum 255 characters long
     * with a valid group name
     */
    public function testAddGroupDescTooLong()
    {
        $groupToCreate = array(
            "groupName" => "My New Cool Group",
            "groupDesc" => "vePO25pU61iG8UbYFVpc4VBhwuIPpIJAE1KbNpa2QGYCQk
                P9KGZODv4au49aVQuuW95ub3vvLGFxuX4rxqKnGRVEnLHkHl5d7j91k6SESza2MLTf4GfuL652L6ck9o2O5
                vQzEwrD9YkzJohZIEU9weSoHremiED6HQJkSS4gF4JZchVPTSgV12B1kieuid9mlDOvLlRYCft3D9vejg5PExWEAJFPVs
                Bqvdk7yBpyFebqKC8HCZk1G3X1GbIXT7g4",
            "groupType" => "standard");

        $url = "localhost/app_test.php/group/startgroup/" . urlencode(json_encode($groupToCreate));
        $username = 'groupcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->em->getRepository(Group::class);

        //Assert that there is no data found
        $this->assertEquals(null, $this->record);


    }

    /**
     * Test that the group name is valid with a valid group name
     */
    public function testAddGroupValidNameValidDesc()
    {
        $groupToCreate = array(
            "groupName" => "Newest Coolest Group",
            "groupDesc" => "Its the best group everrrrrrrrrr",
            "groupType" => "standard");

        $url = "localhost/app_test.php/group/startgroup/" . urlencode(json_encode($groupToCreate));
        $username = 'groupcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $groups = $this->em->getRepository(Group::class)->findOneBy(array('groupName' => "Newest+Coolest+Group"));


        //$this->assertTrue($response->body->status === 'success');
        $this->assertEquals("success", $response->body->status);
    }

    public function testAddGroupExtraField()
    {
        $groupToCreate = array(
            "groupName" => "My New Cool Group",
            "groupDesc" => "The coolest group there ever was",
            "groupType" => "standard",
            "blep" => "abcdefghijklmnop");

        $url = "localhost/app_test.php/group/startgroup/" . urlencode(json_encode($groupToCreate));
        $username = 'groupcontrollertest@yahoo.ca';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->em->getRepository(Group::class);

        $groups = $this->em->getRepository(Group::class)->findOneBy(array('groupName' => "My+New+Cool+Group"));



        //Assert that there is no data found
        //$this->assertTrue(empty($response->body->data));
        $this->assertEquals("success", $response->body->status);

    }

    /**
     * deletes the created group and tears down the database connection
     */
    public function tearDown()
    {
        $application = new Application(self::$kernel);
        $application->setAutoExit(false);

        $options = array('command' => 'doctrine:database:drop', '--force' => true);
        $application->run(new ArrayInput($options));

        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}
