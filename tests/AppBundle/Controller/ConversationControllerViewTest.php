<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\User;
use AppBundle\Entity\Message;
use AppBundle\DataFixtures\UserViewsMessageFixtures;
use Tests\AppBundle\DatabasePrimer;

/**
 * Tests to ensure conversation lists are retrieved correctly.
 *
 * @version 1.0
 * @author cst231
 */
class ConversationControllerViewTest extends WebTestCase
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

        //Load fixtures
        $fixture = new UserViewsMessageFixtures();
        $fixture->load($this->em);

    }

    /**
     * User views conversations success and only gets back the first 20 conversations
     */
    public function testViewConversationsSuccessPageOne()
    {

        $url = "http://127.0.0.1/app_test.php/conversation/view/1";

        $username = 'vmBucky@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals(20, count($response->body->data));
        $this->assertEquals(0, strlen($response->body->message));

        //Variables to keep track of date and time of last message checked.
        $previousDate = date("Y-m-d");
        $previousTime = date("H:i:s");

        //Loop through all conversations to ensure they are in the correct order
        foreach ($response->body->data as $conversation)
        {
        	$this->assertTrue($previousDate >= $conversation->lastMessage->date);
            if ($previousDate == $conversation->lastMessage->date)
            {
                $this->assertTrue($previousTime >= $conversation->lastMessage->time);
            }
            $previousDate = $conversation->lastMessage->date;
            $previousTime = $conversation->lastMessage->time;
        }

    }

    /**
     * User views conversations success and only gets back the final 5 conversations
     */
    public function testViewConversationsSuccessPageThree()
    {
        //Go to page 2 of the conversations list to grab the date from the last conversation
        $url = "http://127.0.0.1/app_test.php/conversation/view/2";

        $username = 'vmBucky@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        //Variables to keep track of date and time of last message checked.
        $previousDate = $response->body->data[count($response->body->data) - 1 ]->lastMessage->date;
        $previousTime = $response->body->data[count($response->body->data) - 1 ]->lastMessage->time;

        //Then call page 3
        $url = "http://127.0.0.1/app_test.php/conversation/view/3";

        $username = 'vmBucky@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals(5, count($response->body->data));
        $this->assertEquals(0, strlen($response->body->message));

        //Loop through all conversations to ensure they are in the correct order
        foreach ($response->body->data as $conversation)
        {
        	$this->assertTrue($previousDate >= $conversation->lastMessage->date);
            if ($previousDate == $conversation->lastMessage->date)
            {
                $this->assertTrue($previousTime >= $conversation->lastMessage->time);
            }
            $previousDate = $conversation->lastMessage->date;
            $previousTime = $conversation->lastMessage->time;
        }
    }

    /**
     * User views conversations success with no conversations
     */
    public function testViewConversationsSuccessNone()
    {

        $url = "http://127.0.0.1/app_test.php/conversation/view/1";

        $username = 'vmTony@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals(0, count($response->body->data));
        $this->assertEquals(0, strlen($response->body->message));
    }

    /**
     * User views conversations failure, not logged in
     */
    public function testViewConversationsFailureNotLoggedIn()
    {

        $url = "http://127.0.0.1/app_test.php/conversation/view/1";

        $response = \Httpful\Request::get($url)->send();

        $this->assertEquals(302, $response->code);
    }

    /**
     * Teardown instructions for each test
     */
    protected function tearDown()
    {
        //Unload the fixture
        $fixture = new UserViewsMessageFixtures();
        $fixture->unload($this->em);

        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }

}