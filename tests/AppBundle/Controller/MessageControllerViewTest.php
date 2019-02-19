<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\User;
use AppBundle\Entity\Message;
use AppBundle\DataFixtures\UserViewsMessageFixtures;
use Tests\AppBundle\DatabasePrimer;

/**
 * Tests to ensure message lists are retrieved correctly.
 *
 * @version 1.0
 * @author cst231
 */
class MessageControllerViewTest extends WebTestCase
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
     * User views messages success and only gets first 20 messages back
     */
    public function testViewMessagesSuccessPageOne()
    {

        $url = "http://127.0.0.1/app_test.php/message/view/VIEWSMSG-8475-491E-A63D-F9F356F98C83/1";

        $username = 'vmBucky@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals(20, count($response->body->data->messages));
        $this->assertEquals(0, strlen($response->body->message));

        //Variables to keep track of date and time of last message checked.
        $previousDate = 0;
        $previousTime = 0;

        //Loop through all messages to ensure they are in the correct order
        foreach ($response->body->data->messages as $message)
        {
        	$this->assertTrue($previousDate <= $message->date);
            if ($previousDate == $message->date)
            {
                $this->assertTrue($previousTime <= $message->time);
            }
            $previousDate = $message->date;
            $previousTime = $message->time;
        }
    }

    /**
     * User views messages success and only gets the last 4 messages back
     */
    public function testViewMessagesSuccessPageThree()
    {
        //Go to page 2 of the message list so we can get the most recent message date and time
        $url = "http://127.0.0.1/app_test.php/message/view/VIEWSMSG-8475-491E-A63D-F9F356F98C83/2";

        $username = 'vmBucky@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        //Variables to track date and time of the oldest message from page 2
        $lastDate = $response->body->data->messages[0]->date;
        $lastTime = $response->body->data->messages[0]->time;

        //Then go to page 3
        $url = "http://127.0.0.1/app_test.php/message/view/VIEWSMSG-8475-491E-A63D-F9F356F98C83/3";

        $username = 'vmBucky@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals(4, count($response->body->data->messages));
        $this->assertEquals(0, strlen($response->body->message));

        //Set variable for last message in returned messages to save having to type it in every time
        $messages = $response->body->data->messages;
        $lastMessage = $messages[count($messages) - 1];

        //Verify that the date and time of the oldest message from page 2
        //is more recent then the last message returned from page 3
        $this->assertTrue($lastDate >= $lastMessage->date);
        if ($lastDate == $lastMessage->date)
        {
            $this->assertTrue($lastTime >= $lastMessage->time);
        }

        //Variables to keep track of date and time of last message checked.
        $previousDate = 0;
        $previousTime = 0;

        //Loop through each message and ensure that they are in the correct order
        foreach ($messages as $message)
        {
            //Make sure previous date is either the same or less than the next date
            $this->assertTrue($previousDate <= $message->date);
            //If the days are the same, then check the time
            if ($previousDate == $message->date)
            {
                $this->assertTrue($previousTime <= $message->time);
            }
            $previousDate = $message->date;
            $previousTime = $message->time;
        }

    }

    /**
     * User views messages success with no messages
     */
    public function testViewMessagesSuccessNone()
    {
        $url = "http://127.0.0.1/app_test.php/message/view/VIEWSMSG-8475-491E-A63D-F9F356F98C81/1";

        $username = 'vmTony@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals(0, count($response->body->data));
        $this->assertEquals(0, strlen($response->body->message));
    }

    /**
     * User views messages failure, not logged in
     */
    public function testViewMessagesFailureNotLoggedIn()
    {
        $url = "http://127.0.0.1/app_test.php/message/view/VIEWSMSG-8475-491E-A63D-F9F356F98C83/1";

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