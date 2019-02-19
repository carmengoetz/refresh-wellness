<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\User;
use AppBundle\Entity\Message;
use AppBundle\Entity\Conversation;
use AppBundle\DataFixtures\UserSendsMessageFixtures;
use Tests\AppBundle\DatabasePrimer;

/**
 *
 *
 * @version 1.0
 * @author cst213
 */
class UserSendsMessageTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     * @var mixed
     */
    private $em;

    /**
     * Set up for the fixture to boot the kernel
     */
    protected function setUp()
    {
        self::bootKernel();

        //Apply the primer
        DatabasePrimer::prime(self::$kernel);

        //Set entity manager
        $this->em = DatabasePrimer::$entityManager;

        $fixture = new UserSendsMessageFixtures();
        $fixture->load($this->em);

    }

    /**
     * User sends message success
     */
    public function testMessageSendSuccess()
    {
        $messageJSON = array(
            "sender" => "4950F3C6-8475-491E-A63D-F9F356F98C81",
            "receiver" => "4950F3C6-8475-491E-A63D-F9F356F98C82",
            "message" => "Hello!"
        );

        $url = "http://127.0.0.1/app_test.php/message/send/" . urlencode(json_encode($messageJSON));

        $username = 'messageSenderOne@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals(0, strlen($response->body->message));
    }

    /**
     * User sends message to wellness pro who does not have them as a patient
     */
    public function testMessageSendFailureNoRelationship()
    {
        $messageJSON = array(
            "sender" => "4950F3C6-8475-491E-A63D-F9F356F98C81",
            "receiver" => "4950F3C6-8475-491E-A63D-F9F356F98C83",
            "message" => "Hello!"
        );

        $url = "http://127.0.0.1/app_test.php/message/send/" . urlencode(json_encode($messageJSON));

        $username = 'messageSenderOne@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('failure', $response->body->status);
        $this->assertEquals('You cannot message this user without a previously set relationship.', $response->body->message);
    }

    /**
     * User sends message to user who does not exist
     */
    public function testMessageSendFailureUserNotExist()
    {
        $messageJSON = array(
            "sender" => "4950F3C6-8475-491E-A63D-F9F356F98C81",
            "receiver" => "4950F3C6-8475-491E-A63D-F9F356F98C89",
            "message" => ""
        );

        $url = "http://127.0.0.1/app_test.php/message/send/" . urlencode(json_encode($messageJSON));

        $username = 'messageSenderOne@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('failure', $response->body->status);
        $this->assertEquals('Receiver does not exist.', $response->body->message);
    }

    /**
     * User sends message but message has no content
     */
    public function testMessageSendFailureNoContent()
    {
        $messageJSON = array(
            "sender" => "4950F3C6-8475-491E-A63D-F9F356F98C81",
            "receiver" => "4950F3C6-8475-491E-A63D-F9F356F98C82",
            "message" => ""
        );

        $url = "http://127.0.0.1/app_test.php/message/send/" . urlencode(json_encode($messageJSON));

        $username = 'messageSenderOne@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('failure', $response->body->status);
        $this->assertEquals('Cannot send blank message.', $response->body->message);
    }

    /////////////////////////////////////////////////////////////
    // I don't know why but this test fails and passes randomly//
    /////////////////////////////////////////////////////////////
    //Error is coming from line 53 of Messaging.php, trying to get property of non object


    /**
     * User attempts to send message to wellness pro but is not logged in
     */
    public function testMessageSendFailureNotLoggedIn()
    {
        $messageJSON = array(
            "sender" => "",
            "receiver" => "4950F3C6-8475-491E-A63D-F9F356F98C82",
            "message" => "Hello!"
        );

        $url = "http://127.0.0.1/app_test.php/message/send/" . urlencode(json_encode($messageJSON));


        $response = \Httpful\Request::get($url)->send();

        $this->assertEquals(302, $response->code);
    }

    /**
     * User sends message with no receiver
     */
    public function testMessageSendFailureNoReceiver()
    {
        $messageJSON = array(
            "sender" => "4950F3C6-8475-491E-A63D-F9F356F98C81",
            "receiver" => "",
            "message" => "Hello!"
        );

        $url = "http://127.0.0.1/app_test.php/message/send/" . urlencode(json_encode($messageJSON));

        $username = 'messageSenderOne@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('failure', $response->body->status);
        $this->assertEquals('Please provide a receiver.', $response->body->message);
    }



    ////////////////////////////////////////////////////
    // New test added by front end of sending message //
    ////////////////////////////////////////////////////

    /**
     * Test that a conversation object is created in the db when a message is sent
     */
    public function testConversationCreated()
    {
        //Re-used code to send a message
        $messageJSON = array(
            "sender" => "4950F3C6-8475-491E-A63D-F9F356F98C81",
            "receiver" => "4950F3C6-8475-491E-A63D-F9F356F98C82",
            "message" => "Hello!"
        );

        $url = "http://127.0.0.1/app_test.php/message/send/" . urlencode(json_encode($messageJSON));

        $username = 'messageSenderOne@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals(0, strlen($response->body->message));

        //Pulling from the db to see if the conversation was created
        //user one ID is set to that of the sender by default
        $repo = $this->em->getRepository(Conversation::class);
        $existingConvo = $repo->findOneBy(['userOneID'=>"4950F3C6-8475-491E-A63D-F9F356F98C81",
                              'userTwoID'=>"4950F3C6-8475-491E-A63D-F9F356F98C82"]);

        //Pulling the ID from the found object
        $senderID = $existingConvo->getUserOneID()->getUserId();

        //Checking that the ID does match after being pulled from the db
        $this->assertEquals("4950F3C6-8475-491E-A63D-F9F356F98C81", $senderID,
            "Conversations was not created");
    }



    /**
     * Teardown instructions for each test
     */
    protected function tearDown()
    {
        //Unload the fixture
        $fixture = new UserSendsMessageFixtures();
        $fixture->unload($this->em);

        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}