<?php
namespace Tests\AppBundle\Views;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\DataFixtures\AppFixtures;
use Symfony\Component\HttpKernel\Client;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink\Driver;
use Behat\Mink\Driver\Selenium2Driver;
use DMore\ChromeDriver\ChromeDriver;
use AppBundle\DataFixtures\ViewSendMessagesFixtures;
use Tests\AppBundle\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Unit tests for front end of viewing messages
 *
 * @version 1.0
 * @author cst236
 */

class ViewSendMessagesTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     * @var mixed
     */
    private $em;
    private $driver;
    private $mink;

    /**
     * sets up the tests
     */
    protected function setUp()
    {
        //if need to test indv unittests using testmanager, uncomment code below
        $_SERVER['KERNEL_DIR'] = './app';
        self::bootKernel();

        //Apply the primer
        DatabasePrimer::prime(self::$kernel);

        //Set entity manager
        $this->em = DatabasePrimer::$entityManager;

        //Need to create a new fixture for unit tests
        $fixture = new ViewSendMessagesFixtures();
        $fixture->load($this->em);

        $this->driver = new ChromeDriver('http://localhost:9222', null, 'http://127.0.0.1:80');
        $this->mink = new Mink(array('browser' => new Session(new ChromeDriver('http://127.0.0.1:9222', null, 'http://127.0.0.1:80'))));

        $this->mink->setDefaultSessionName('browser');
    }
    /**
     * Tears down the fixtures
     */
    protected function tearDown()
    {
        $application = new Application(self::$kernel);
        $application->setAutoExit(false);

        $options = array('command' => 'doctrine:database:drop', '--force' => true);
        $application->run(new ArrayInput($options));


        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }

    /**
     * Tests to ensure that sending a message from the profile page on desktop with no existing conversation works
     */
    public function testSendMessageFromProfileNEWDesktop()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "messFred@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Set the screen size
        $session->resizeWindow(1100, 800, 'current');

        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/profile/wilma-flintstone');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //click on message button
        $page->find("css", "#control-panel-desktop .send-message")->click();

        //Wait for modal to load
        $session->wait(1000);

        //Assert the modal is visible
        $this->assertTrue($page->find("css", "#send-message-modal")->isVisible());

        //Type in the box
        $page->find("css", "#message-text-box")->setValue("Yabba-dabba-doo!");

        //Click send
        $page->find("css", "#message-send-button")->click();
        $date = Date("Y-m-d");
        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');



        $this->assertFalse($page->find("css", ".modal" )->isVisible());

        //Click inbox link
        $page->find("css", "#viewConversations")->click();

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        $convo = $page->find("css", ".convoItm");


        //Assert that first conversation in list is with wilma and it says yabba dabba doo
        $this->assertEquals("Wilma Flintstone", $convo->find("css", ".sender")->getText());

        $this->assertEquals("Yabba-dabba-doo!",$convo->find("css", ".contents")->getText());
        $this->assertEquals($date,$convo->find("css", ".date")->getText());
    }

    /**
     * Tests to ensure that sending a message from the profile page on mobile with no existing conversation works
     */
    public function testSendMessageFromProfileNEWMobile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "messFred@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);
        //Set the screen size
        $session->resizeWindow(900, 800, 'current');

        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/profile/wilma-flintstone');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //click on message button
        $page->find("css", "#control-panel-desktop .send-message")->click();

        //Wait for modal to load
        $session->wait(1000);

        //Assert the modal is visible
        $this->assertTrue($page->find("css", "#send-message-modal")->isVisible());

        //Type in the box
        $page->find("css", "#message-text-box")->setValue("Yabba-dabba-doo!");

        //Click send
        $page->find("css", "#message-send-button")->click();
        $date = Date("Y-m-d");
        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');



        $this->assertFalse($page->find("css", ".modal" )->isVisible());

        //Click inbox link
        $page->find("css", "#viewConversations")->click();

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        $convo = $page->find("css", ".convoItm");


        //Assert that first conversation in list is with wilma and it says yabba dabba doo
        $this->assertEquals("Wilma Flintstone", $convo->find("css", ".sender")->getText());

        $this->assertEquals("Yabba-dabba-doo!",$convo->find("css", ".contents")->getText());
        $this->assertEquals($date,$convo->find("css", ".date")->getText());
    }

    public function testSendNewMessageFromConvoPageNEW()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "messFred@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);


        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/conversation/viewconversations');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Select recipient
        $page->find("css", "#select-recipient")->setValue("barney-rubble");


        //click on message button
        $page->find("css", "#send-message")->click();

        //Wait for modal to load
        $session->wait(1000);

        //Assert the modal is visible
        $this->assertTrue($page->find("css", "#send-message-modal")->isVisible());



        //Type in the box
        $page->find("css", "#message-text-box")->setValue("Yabba-dabba-doo!");

        //Click send
        $page->find("css", "#message-send-button")->click();

        $date = Date("Y-m-d");


        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Wait for modal to unload

        $this->assertFalse($page->find("css", ".modal" )->isVisible());

        $convo = $page->find("css", ".convoItm");


        //Assert that first conversation in list is with wilma and it says yabba dabba doo
        $this->assertEquals("Barney Rubble", $convo->find("css", ".sender")->getText());

        $this->assertEquals("Yabba-dabba-doo!",$convo->find("css", ".contents")->getText());

        $this->assertEquals($date,$convo->find("css", ".date")->getText());



    }

    public function testSendMessageFromProfileEXISTSDesktop()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "messFred@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);
        //Set the screen size
        $session->resizeWindow(1100, 800, 'current');

        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/profile/betty-rubble');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //click on message button
        $page->find("css", "#control-panel-desktop .send-message")->click();

        //Wait for modal to load
        $session->wait(1000);

        //Assert the modal is visible
        $this->assertTrue($page->find("css", "#send-message-modal")->isVisible());

        //Type in the box
        $page->find("css", "#message-text-box")->setValue("Yabba-dabba-doo!");

        //Click send
        $page->find("css", "#message-send-button")->click();
        $date = Date("Y-m-d");
        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        $this->assertFalse($page->find("css", ".modal" )->isVisible());

        //Click inbox link
        $page->find("css", "#viewConversations")->click();

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        $convo = $page->find("css", ".convoItm");


        //Assert that first conversation in list is with wilma and it says yabba dabba doo
        $this->assertEquals("Betty Rubble", $convo->find("css", ".sender")->getText());

        $this->assertEquals("Yabba-dabba-doo!",$convo->find("css", ".contents")->getText());
        $this->assertEquals($date,$convo->find("css", ".date")->getText());

    }

    public function testSendMessageFromProfileEXISTSMobile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "messFred@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);
        //Set the screen size
        $session->resizeWindow(900, 800, 'current');

        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/profile/betty-rubble');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //click on message button
        $page->find("css", "#control-panel-desktop .send-message")->click();

        //Wait for modal to load
        $session->wait(1000);

        //Assert the modal is visible
        $this->assertTrue($page->find("css", "#send-message-modal")->isVisible());

        //Type in the box
        $page->find("css", "#message-text-box")->setValue("Yabba-dabba-doo!");

        //Click send
        $page->find("css", "#message-send-button")->click();
        $date = Date("Y-m-d");
        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        $this->assertFalse($page->find("css", ".modal" )->isVisible());

        //Click inbox link
        $page->find("css", "#viewConversations")->click();

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        $convo = $page->find("css", ".convoItm");


        //Assert that first conversation in list is with wilma and it says yabba dabba doo
        $this->assertEquals("Betty Rubble", $convo->find("css", ".sender")->getText());

        $this->assertEquals("Yabba-dabba-doo!",$convo->find("css", ".contents")->getText());
        $this->assertEquals($date,$convo->find("css", ".date")->getText());

    }

    public function testSendNewMessageFromConvoPageEXISTS()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "messFred@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);


        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/conversation/viewconversations');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();



        //Select recipient
        $page->find("css", "#select-recipient")->setValue("pebbles-flintstone");

        //click on message button
        $page->find("css", "#send-message")->click();
        $date = Date("Y-m-d");
        //Wait for modal to load
        $session->wait(1000);

        //Type in the box
        $page->find("css", "#message-text-box")->setValue("Yabba-dabba-doo!");

        //Click send
        $page->find("css", "#message-send-button")->click();

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        $this->assertFalse($page->find("css", ".modal" )->isVisible());

        $convo = $page->find("css", ".convoItm");


        //Assert that first conversation in list is with wilma and it says yabba dabba doo
        $this->assertEquals("Pebbles Flintstone", $convo->find("css", ".sender")->getText());

        $this->assertEquals("Yabba-dabba-doo!",$convo->find("css", ".contents")->getText());
        $this->assertEquals($date,$convo->find("css", ".date")->getText());

    }


    public function testDropdownPopulatedWithCorrectRelationshipsInAlphaOrder()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "messFred@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);


        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/conversation/viewconversations');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $page->find('css', 'a.chosen-single')->click();

        $this->assertEquals(true, $page->find('css', '.chosen-drop')->isVisible());

        $session->wait(1000);
        $results = $page->findAll('css', '.active-result');

        $this->assertEquals(4, count($results) );

        $this->assertEquals("Barney Rubble", $results[0]->getText());
        $this->assertEquals("Betty Rubble", $results[1]->getText());
        $this->assertEquals("Pebbles Flintstone", $results[2]->getText());
        $this->assertEquals("Wilma Flintstone", $results[3]->getText());




    }

    public function testSendMessageFromInsideConversation()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "messFred@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);


        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/conversation/viewconversations');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $page->find("css", ".convoItm")->click();


        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Type in the box
        $page->find("css", "#message-convo-text-box")->setValue("Yabba-dabba-doo!");

        //Click send
        $page->find("css", "#convo-msg-send-btn")->click();
        $date = Date("Y-m-d");
        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        $session->wait(1000);
        //Get last message
        $allmess = $page->findAll("css", ".contents");

        $this->assertEquals("Yabba-dabba-doo!",$allmess[count($allmess) - 1]->getText());
        $this->assertEquals($date,$page->findAll("css", ".date")[count($page->findAll("css", ".date")) - 1]->getText());
    }


    public function testSendMessageFromInsideConversationError()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "messFred@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);


        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/conversation/viewconversations');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $page->find("css", ".convoItm")->click();


        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        $session->wait(1000);

        //Click send with no message
        $page->find("css", "#convo-msg-send-btn")->click();


        $session->wait(500);



        $this->assertEquals(true, $page->find('css', '#jsonError')->isVisible());

        $this->assertEquals("Cannot send blank message.", $page->find('css', '#jsonError')->getText());

        $session->wait(5000);
        $this->assertEquals(false, $page->find('css', '#jsonError')->isVisible());
    }


    public function testSendMessageCauseError()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "messFred@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);


        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/conversation/viewconversations');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Select recipient
        $page->find("css", "#select-recipient")->setValue("bambam-rubble");

        //click on message button
        $page->find("css", "#send-message")->click();

        //Wait for modal to load
        $session->wait(1000);



        //Type in the box
        $page->find("css", "#message-text-box")->setValue("Yabba-dabba-doo!");

        //Click send
        $page->find("css", "#message-send-button")->click();

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Assert that there is an error
        $this->assertEquals(true, $page->find('css', '#notification')->isVisible());
        $this->assertEquals("Please provide a receiver.", $page->find('css', '#notification')->getText());



    }


    public function testSendMessageCauseErrorNoMessage()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "messFred@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);


        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/conversation/viewconversations');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Select recipient
        $page->find("css", "#select-recipient")->setValue("barney-rubble");

        //click on message button
        $page->find("css", "#send-message")->click();

        //Wait for modal to load
        $session->wait(1000);





        //Click send
        $page->find("css", "#message-send-button")->click();



        //Assert that there is an error
        $this->assertEquals(true, $page->find('css', '#msg-error')->isVisible());
        $this->assertEquals("Please provide a message.", $page->find('css', '#msg-error')->getText());

        $session->wait(5000);
        $this->assertEquals(false, $page->find('css', '#msg-error')->isVisible());


    }

    public function testSendMessageNoRelationships()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "messBamBam@gmail.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);


        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/conversation/viewconversations');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //click on message button
        $this->assertEquals(true, $page->find("css", "#send-message")->hasAttribute('disabled'));




    }

}