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
use AppBundle\DataFixtures\ViewForMessagesFixture;
use Tests\AppBundle\DatabasePrimer;


/**
 * Unit tests for front end of viewing messages
 *
 * @version 1.0
 * @author cst236
 */

class ViewMessagesTest extends WebTestCase
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
        $fixture = new ViewForMessagesFixture();
        $fixture->load($this->em);

        $this->driver = new ChromeDriver('http://localhost:9222', null, 'http://127.0.0.1:80');
        $this->mink = new Mink(array('browser' => new Session(new ChromeDriver('http://127.0.0.1:9222', null, 'http://127.0.0.1:80'))));

        $this->mink->setDefaultSessionName('browser');
    }

    //public function testViewConversationsNotLoggedIn()
    //{
    //    //Get a local copy of the session
    //    $session = $this->mink->getSession();

    //    //Visit the page navigate to "home page" and click the link that leads to our page.
    //    //This link needs to be updated in all tests
    //    $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

    //    //Wait for page to load
    //    $session->wait(60000, '(0 === jQuery.active)');

    //    //Get the page node
    //    $page = $session->getPage();

    //    //Navigate to the conversation page
    //    $navigate = $page->find("css", "#viewConversations")->click();

    //    //Wait for the new page to load
    //    $session->wait(60000, '(0 === jQuery.active)');
    //    $session->wait(1000);
    //    //Contents that should be displayed when the page loads
    //    #region Conversation contents visable
    //    //ID based
    //    $mainWrapper = $page->find("css", "#mainWrapper");
    //    $this->assertTrue($mainWrapper->isVisible());

    //    $headerText = $page->find("css", "#headerText");
    //    $this->assertTrue($headerText->isVisible());

    //    $session->wait(500);
    //    $jsonError = $page->find("css", "#jsonError");
    //    $this->assertTrue($jsonError->isVisible());

    //    //Class based
    //    $errorMsg = $page->find("css", ".errorMsg");
    //    $this->assertTrue($errorMsg->isVisible());
    //    #endregion

    //    $session->wait(500);
    //    //Contents that should be hidden when the page loads
    //    #region Conversation contents hidden
    //    //Id Based

    //    $msgContainer = $page->find("css", "#msgContainer");
    //    $this->assertFalse($msgContainer->isVisible());

    //    //Containing element is hidden
    //    $headings = $page->find("css", "#headings");
    //    $this->assertFalse($headings->isVisible());

    //    $convoBox = $page->find("css", "#convoBox");
    //    $this->assertFalse($convoBox->isVisible());

    //    $paginationHolder = $page->find("css", "#pagination-holder");
    //    $this->assertFalse($paginationHolder->isVisible());

    //    $prev = $page->find("css", "#prev");
    //    $this->assertFalse($prev->isVisible());

    //    $next = $page->find("css", "#next");
    //    $this->assertFalse($next->isVisible());

    //    $prevPage = $page->find("css", "#prevPage");
    //    $this->assertFalse($prevPage->isVisible());

    //    $nextPage = $page->find("css", "#nextPage");
    //    $this->assertFalse($nextPage->isVisible());



    //    //Class based


    //    $pagination = $page->find("css", ".pagination");
    //    $this->assertFalse($pagination->isVisible());

    //    $pageItem = $page->find("css", ".page-item");
    //    $this->assertFalse($pageItem->isVisible());

    //    $navLink = $page->find("css", ".navLink");
    //    $this->assertFalse($navLink->isVisible());

    //    $head = $page->find("css", ".head");
    //    $this->assertFalse($head->isVisible());

    //    $noConvoMsgMobile = $page->find("css", ".noConvoMsg");
    //    $this->assertFalse($noConvoMsgMobile->isVisible());
    //    #endregion

    //    $text = $errorMsg->getText();

    //    //Make sure the proper message is displayed to the user
    //    $this->assertTrue($text == 'User is not logged in.', "Values are not equal");

    //    //Resizing the window to go to mobile view
    //    $session->resizeWindow(700, 900, 'current');

    //    //Wait for the new page to load
    //    $session->wait(60000, '(0 === jQuery.active)');

    //    //Contents that should be displayed when the page loads
    //    #region Conversation contents visable
    //    //ID based
    //    $mainWrapper = $page->find("css", "#mainWrapper");
    //    $this->assertTrue($mainWrapper->isVisible());

    //    $headerText = $page->find("css", "#headerText");
    //    $this->assertTrue($headerText->isVisible());

    //    $session->wait(500);
    //    $jsonError = $page->find("css", "#jsonError");
    //    $this->assertTrue($jsonError->isVisible());

    //    //Class based
    //    $errorMsg = $page->find("css", ".errorMsg");
    //    $this->assertTrue($errorMsg->isVisible());
    //    #endregion

    //    $session->wait(500);
    //    //Contents that should be hidden when the page loads
    //    #region Conversation contents hidden
    //    //Id Based

    //    $msgContainer = $page->find("css", "#msgContainer");
    //    $this->assertFalse($msgContainer->isVisible());

    //    //Containing element is hidden
    //    $headings = $page->find("css", "#headings");
    //    $this->assertFalse($headings->isVisible());

    //    $convoBox = $page->find("css", "#convoBox");
    //    $this->assertFalse($convoBox->isVisible());

    //    $paginationHolder = $page->find("css", "#pagination-holder");
    //    $this->assertFalse($paginationHolder->isVisible());

    //    $prev = $page->find("css", "#prev");
    //    $this->assertFalse($prev->isVisible());

    //    $next = $page->find("css", "#next");
    //    $this->assertFalse($next->isVisible());

    //    $prevPage = $page->find("css", "#prevPage");
    //    $this->assertFalse($prevPage->isVisible());

    //    $nextPage = $page->find("css", "#nextPage");
    //    $this->assertFalse($nextPage->isVisible());



    //    //Class based


    //    $pagination = $page->find("css", ".pagination");
    //    $this->assertFalse($pagination->isVisible());

    //    $pageItem = $page->find("css", ".page-item");
    //    $this->assertFalse($pageItem->isVisible());

    //    $navLink = $page->find("css", ".navLink");
    //    $this->assertFalse($navLink->isVisible());

    //    $head = $page->find("css", ".head");
    //    $this->assertFalse($head->isVisible());

    //    $noConvoMsgMobile = $page->find("css", ".noConvoMsg");
    //    $this->assertFalse($noConvoMsgMobile->isVisible());
    //    #endregion

    //    $text = $errorMsg->getText();

    //    //Make sure the proper message is displayed to the user
    //    $this->assertTrue($text == 'User is not logged in.', "Values are not equal");
    //}

    /**
     * Test to ensure that the page loads your conversations correctly on desktop view
     */
    public function testViewConversationsPageDesktop()
    {

        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Make sure this is a real user in the fixture
        $un = "messageViewerOne@gmail.com";
        $pw = "password";

        //log in with your credentials
        $session->setBasicAuth($un, $pw);

        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Navigate to the conversation page
        $navigate = $page->find("css", "#viewConversations")->click();

        //Wait for the new page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        //Contents that should be displayed when the page loads
        #region Conversation contents visable
        //ID based
        $mainWrapper = $page->find("css", "#mainWrapper");
        $this->assertTrue($mainWrapper->isVisible());

        $headerText = $page->find("css", "#headerText");
        $this->assertTrue($headerText->isVisible());

        $msgContainer = $page->find("css", "#msgContainer");
        $this->assertTrue($msgContainer->isVisible());

        $headings = $page->find("css", "#headings");
        $this->assertTrue($headings->isVisible());

        $convoBox = $page->find("css", "#convoBox");
        $this->assertTrue($convoBox->isVisible());

        //Class based
        $head = $page->find("css", ".head");
        $this->assertTrue($head->isVisible());

        $convoItm = $page->find("css", ".convoItm");
        $this->assertTrue($convoItm->isVisible());

        $read = $page->find("css", ".read");
        $this->assertTrue($read->isVisible());

        $sender = $page->find("css", ".sender");
        $this->assertTrue($sender->isVisible());

        $date = $page->find("css", ".date");
        $this->assertTrue($date->isVisible());

        $contents = $page->find("css", ".contents");
        $this->assertTrue($contents->isVisible());
        #endregion


        //Contents that should be hidden when the page loads
        #region Conversation contents hidden
        //Id Based
        $paginationHolder = $page->find("css", "#pagination-holder");
        $this->assertFalse($paginationHolder->isVisible());

        $prev = $page->find("css", "#prev");
        $this->assertFalse($prev->isVisible());

        $next = $page->find("css", "#next");
        $this->assertFalse($next->isVisible());

        $prevPage = $page->find("css", "#prevPage");
        $this->assertFalse($prevPage->isVisible());

        $nextPage = $page->find("css", "#nextPage");
        $this->assertFalse($nextPage->isVisible());

        $jsonError = $page->find("css", "#jsonError");
        $this->assertFalse($jsonError->isVisible());

        //Class based

        $noConvoMsg = $page->find("css", ".noConvoMsg");
        $this->assertFalse($noConvoMsg->isVisible());

        $errorMsg = $page->find("css", ".errorMsg");
        $this->assertFalse($errorMsg->isVisible());

        $pagination = $page->find("css", ".pagination");
        $this->assertFalse($pagination->isVisible());

        $pageItem = $page->find("css", ".page-item");
        $this->assertFalse($pageItem->isVisible());

        $navLink = $page->find("css", ".navLink");
        $this->assertFalse($navLink->isVisible());
        #endregion

        //Check that the values passed are correct
        $this->assertEquals($sender->getText(), 'Tony Stark'); //Ensure the text of the message sender is correct
        $this->assertEquals($date->getText(), '2250-02-05'); //Ensure the date is displayed correctly
        $this->assertEquals($contents->getText(), "Don't sell my shield while I'm gone");
    }

    /**
     * Test to ensure that the page loads your conversations correctly on mobile view
     * Same as previous test, only with the window resized
     */
    public function testViewConversationsPageMobile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Make sure this is a real user in the fixture
        $un = "messageViewerOne@gmail.com";
        $pw = "password";

        //log in with your credentials
        $session->setBasicAuth($un, $pw);

        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Navigate to the conversation page
        $navigate = $page->find("css", "#viewConversations")->click();

        //Resizing the window to go to mobile view
        $session->resizeWindow(700, 900, 'current');

        //Wait for the new page to load
        //Had to add a second wait, sometimes would run too fast to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);

        //Contents that should be displayed when the page loads
        #region Conversation contents visable
        //ID based
        $mainWrapper = $page->find("css", "#mainWrapper");
        $this->assertTrue($mainWrapper->isVisible());

        $headerText = $page->find("css", "#headerText");
        $this->assertTrue($headerText->isVisible());

        $msgContainer = $page->find("css", "#msgContainer");
        $this->assertTrue($msgContainer->isVisible());

        $headings = $page->find("css", "#headings");
        $this->assertTrue($headings->isVisible());

        $convoBox = $page->find("css", "#convoBox");
        $this->assertTrue($convoBox->isVisible());

        //Class based
        $head = $page->find("css", ".head");
        $this->assertTrue($head->isVisible());

        $convoItm = $page->find("css", ".convoItm");
        $this->assertTrue($convoItm->isVisible());

        $read = $page->find("css", ".read");
        $this->assertTrue($read->isVisible());

        $sender = $page->find("css", ".sender");
        $this->assertTrue($sender->isVisible());

        $date = $page->find("css", ".date");
        $this->assertTrue($date->isVisible());

        $contents = $page->find("css", ".contents");
        $this->assertTrue($contents->isVisible());
        #endregion


        //Contents that should be hidden when the page loads
        #region Conversation contents hidden
        //Id Based
        $paginationHolder = $page->find("css", "#pagination-holder");
        $this->assertFalse($paginationHolder->isVisible());

        $prev = $page->find("css", "#prev");
        $this->assertFalse($prev->isVisible());

        $next = $page->find("css", "#next");
        $this->assertFalse($next->isVisible());

        $prevPage = $page->find("css", "#prevPage");
        $this->assertFalse($prevPage->isVisible());

        $nextPage = $page->find("css", "#nextPage");
        $this->assertFalse($nextPage->isVisible());

        $jsonError = $page->find("css", "#jsonError");
        $this->assertFalse($jsonError->isVisible());

        //Class based

        $noConvoMsg = $page->find("css", ".noConvoMsg");
        $this->assertFalse($noConvoMsg->isVisible());

        $errorMsg = $page->find("css", ".errorMsg");
        $this->assertFalse($errorMsg->isVisible());

        $pagination = $page->find("css", ".pagination");
        $this->assertFalse($pagination->isVisible());

        $pageItem = $page->find("css", ".page-item");
        $this->assertFalse($pageItem->isVisible());

        $navLink = $page->find("css", ".navLink");
        $this->assertFalse($navLink->isVisible());
        #endregion

        //Check that the values passed are correct
        $this->assertEquals($sender->getText(), 'Tony Stark'); //Ensure the text of the message sender is correct
        $this->assertEquals($date->getText(), '2250-02-05'); //Ensure the date is displayed correctly
        $this->assertEquals($contents->getText(), "Don't sell my shield while I'm gone");
    }

    /*
     * Test to ensure that the user has no conversations in their mailbox in desktop view
     */
    public function testViewNoConversationsDesktop()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Make sure this is a real user in the fixture
        $un = "messageViewerFive@gmail.com";
        $pw = "password";

        //log in with your credentials
        $session->setBasicAuth($un, $pw);

        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Navigate to the conversation page
        $navigate = $page->find("css", "#viewConversations")->click();

        //Wait for the new page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        //Contents that should be displayed when the page loads
        #region Conversation contents visable
        //ID based
        $mainWrapper = $page->find("css", "#mainWrapper");
        $this->assertTrue($mainWrapper->isVisible());

        $headerText = $page->find("css", "#headerText");
        $this->assertTrue($headerText->isVisible());

        //Class based
        $noConvoMsg = $page->find("css", ".noConvoMsg");
        $this->assertTrue($noConvoMsg->isVisible());
        #endregion


        //Contents that should be hidden when the page loads
        #region Conversation contents hidden
        //Id Based

        $msgContainer = $page->find("css", "#msgContainer");
        $this->assertFalse($msgContainer->isVisible());

        $headings = $page->find("css", "#headings");
        $this->assertFalse($headings->isVisible());

        $convoBox = $page->find("css", "#convoBox");
        $this->assertFalse($convoBox->isVisible());

        $paginationHolder = $page->find("css", "#pagination-holder");
        $this->assertFalse($paginationHolder->isVisible());

        $prev = $page->find("css", "#prev");
        $this->assertFalse($prev->isVisible());

        $next = $page->find("css", "#next");
        $this->assertFalse($next->isVisible());

        $prevPage = $page->find("css", "#prevPage");
        $this->assertFalse($prevPage->isVisible());

        $nextPage = $page->find("css", "#nextPage");
        $this->assertFalse($nextPage->isVisible());

        $jsonError = $page->find("css", "#jsonError");
        $this->assertFalse($jsonError->isVisible());

        //Class based
        $errorMsg = $page->find("css", ".errorMsg");
        $this->assertFalse($errorMsg->isVisible());

        $pagination = $page->find("css", ".pagination");
        $this->assertFalse($pagination->isVisible());

        $pageItem = $page->find("css", ".page-item");
        $this->assertFalse($pageItem->isVisible());

        $navLink = $page->find("css", ".navLink");
        $this->assertFalse($navLink->isVisible());

        $head = $page->find("css", ".head");
        $this->assertFalse($head->isVisible());
        #endregion

        //Make sure the proper message is displayed to the user
        $this->assertEquals($noConvoMsg->getText(), 'Your mailbox is currently empty.', "Values are not equal");

    }

    /*
     * Test to ensure that the user has no conversations in their mailbox in mobile view
     */
    public function testViewNoConversationsMobile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Make sure this is a real user in the fixture
        $un = "messageViewerFive@gmail.com";
        $pw = "password";

        //log in with your credentials
        $session->setBasicAuth($un, $pw);

        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Navigate to the conversation page
        $navigate = $page->find("css", "#viewConversations")->click();

        //Resizing the window to go to mobile view
        $session->resizeWindow(700, 900, 'current');

        //Wait for the new page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        //Contents that should be displayed when the page loads
        #region Conversation contents visable
        //ID based
        $mainWrapper = $page->find("css", "#mainWrapper");
        $this->assertTrue($mainWrapper->isVisible());

        $headerText = $page->find("css", "#headerText");
        $this->assertTrue($headerText->isVisible());

        //Class based
        $noConvoMsgMobile = $page->find("css", ".noConvoMsg");
        $this->assertTrue($noConvoMsgMobile->isVisible());
        #endregion


        //Contents that should be hidden when the page loads
        #region Conversation contents hidden
        //Id Based

        $msgContainer = $page->find("css", "#msgContainer");
        $this->assertFalse($msgContainer->isVisible());

        $headings = $page->find("css", "#headings");
        $this->assertFalse($headings->isVisible());

        $convoBox = $page->find("css", "#convoBox");
        $this->assertFalse($convoBox->isVisible());

        $paginationHolder = $page->find("css", "#pagination-holder");
        $this->assertFalse($paginationHolder->isVisible());

        $prev = $page->find("css", "#prev");
        $this->assertFalse($prev->isVisible());

        $next = $page->find("css", "#next");
        $this->assertFalse($next->isVisible());

        $prevPage = $page->find("css", "#prevPage");
        $this->assertFalse($prevPage->isVisible());

        $nextPage = $page->find("css", "#nextPage");
        $this->assertFalse($nextPage->isVisible());

        $jsonError = $page->find("css", "#jsonError");
        $this->assertFalse($jsonError->isVisible());

        //Class based
        $errorMsg = $page->find("css", ".errorMsg");
        $this->assertFalse($errorMsg->isVisible());

        $pagination = $page->find("css", ".pagination");
        $this->assertFalse($pagination->isVisible());

        $pageItem = $page->find("css", ".page-item");
        $this->assertFalse($pageItem->isVisible());

        $navLink = $page->find("css", ".navLink");
        $this->assertFalse($navLink->isVisible());

        $head = $page->find("css", ".head");
        $this->assertFalse($head->isVisible());
        #endregion

        $text = $noConvoMsgMobile->getText();

        //Make sure the proper message is displayed to the user
        $this->assertTrue($text == 'Your mailbox is currently empty.', "Values are not equal");
    }

    /*
     *Test that 20 conversations load by default, when next is clicked
     *another 20 are loaded. Click next again and there are 10 loaded and
     *the next button is gone
     */
    public function testView50ConversationsDesktop()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Make sure this is a real user in the fixture
        $un = "messageViewerFour@gmail.com";
        $pw = "password";

        //log in with your credentials
        $session->setBasicAuth($un, $pw);

        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Navigate to the conversation page
        $navigate = $page->find("css", "#viewConversations")->click();

        //Wait for the new page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        //Contents that should be displayed when the page loads
        #region Conversation contents visable
        //ID based
        $mainWrapper = $page->find("css", "#mainWrapper");
        $this->assertTrue($mainWrapper->isVisible());

        $headerText = $page->find("css", "#headerText");
        $this->assertTrue($headerText->isVisible());

        $msgContainer = $page->find("css", "#msgContainer");
        $this->assertTrue($msgContainer->isVisible());

        $headings = $page->find("css", "#headings");
        $this->assertTrue($headings->isVisible());

        $convoBox = $page->find("css", "#convoBox");
        $this->assertTrue($convoBox->isVisible());

        $paginationHolder = $page->find("css", "#pagination-holder");
        $this->assertTrue($paginationHolder->isVisible());

        $prev = $page->find("css", "#prev");
        $this->assertTrue($prev->isVisible());

        $next = $page->find("css", "#next");
        $this->assertTrue($next->isVisible());

        $nextPage = $page->find("css", "#nextPage");
        $this->assertTrue($nextPage->isVisible());

        //Class based
        $head = $page->find("css", ".head");
        $this->assertTrue($head->isVisible());

        $convoItm = $page->find("css", ".convoItm");
        $this->assertTrue($convoItm->isVisible());

        $read = $page->find("css", ".read");
        $this->assertTrue($read->isVisible());

        $sender = $page->find("css", ".sender");
        $this->assertTrue($sender->isVisible());

        $date = $page->find("css", ".date");
        $this->assertTrue($date->isVisible());

        $contents = $page->find("css", ".contents");
        $this->assertTrue($contents->isVisible());

        $pagination = $page->find("css", ".pagination");
        $this->assertTrue($pagination->isVisible());

        $pageItem = $page->find("css", ".page-item");
        $this->assertTrue($pageItem->isVisible());
        #endregion


        //Contents that should be hidden when the page loads
        #region Conversation contents hidden
        //Id Based
        $jsonError = $page->find("css", "#jsonError");
        $this->assertFalse($jsonError->isVisible());

        $prevPage = $page->find("css", "#prevPage");
        $this->assertFalse($prevPage->isVisible());

        //Class based

        $noConvoMsg = $page->find("css", ".noConvoMsg");
        $this->assertFalse($noConvoMsg->isVisible());

        $errorMsg = $page->find("css", ".errorMsg");
        $this->assertFalse($errorMsg->isVisible());

        $navLink = $page->find("css", ".navLink");
        $this->assertFalse($navLink->isVisible());
        #endregion

        $totalConversations = 0;

        while($nextPage->isVisible())
        {
            $totalConversations += count($page->findAll("css", ".convoItm"));

            $navigate = $nextPage->click();

            //Wait for page to load
            $session->wait(60000, '(0 === jQuery.active)');
        }

        //Checking how many conversations are on the last page
        $totalConversations += count($page->findAll("css", ".convoItm"));

        //Checking that the nav buttons are appropriately displayed or hidden
        $prevPage = $page->find("css", "#prevPage");
        $this->assertTrue($prevPage->isVisible());

        $nextPage = $page->find("css", "#nextPage");
        $this->assertFalse($nextPage->isVisible());

        $navLink = $page->find("css", ".navLink");
        $this->assertTrue($navLink->isVisible());

        //Checking that the correct number of conversations have been found.
        $this->assertEquals($totalConversations, 50, "Total conversations was not 50");
    }

    /*
     *Test that 20 conversations load by default, when scrolled to the bottom
     *another 20 are loaded. Scroll down again and there are 50 loaded to the page
     */
    public function testView50ConversationsMobile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Make sure this is a real user in the fixture
        $un = "messageViewerFour@gmail.com";
        $pw = "password";

        //log in with your credentials
        $session->setBasicAuth($un, $pw);

        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Navigate to the conversation page
        $navigate = $page->find("css", "#viewConversations")->click();

        //Resizing the window to go to mobile view
        $session->resizeWindow(700, 900, 'current');

        //Wait for the new page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);

        //Counting the initial page load
        $this->assertTrue(count($page->findAll("css", ".convoItm")) == 20);

        //Contents that should be displayed when the page loads
        #region Conversation contents visable
        //ID based
        $mainWrapper = $page->find("css", "#mainWrapper");
        $this->assertTrue($mainWrapper->isVisible());

        $headerText = $page->find("css", "#headerText");
        $this->assertTrue($headerText->isVisible());

        $msgContainer = $page->find("css", "#msgContainer");
        $this->assertTrue($msgContainer->isVisible());

        $headings = $page->find("css", "#headings");
        $this->assertTrue($headings->isVisible());

        $convoBox = $page->find("css", "#convoBox");
        $this->assertTrue($convoBox->isVisible());

        //Class based
        $head = $page->find("css", ".head");
        $this->assertTrue($head->isVisible());

        $convoItm = $page->find("css", ".convoItm");
        $this->assertTrue($convoItm->isVisible());

        $read = $page->find("css", ".read");
        $this->assertTrue($read->isVisible());

        $sender = $page->find("css", ".sender");
        $this->assertTrue($sender->isVisible());

        $date = $page->find("css", ".date");
        $this->assertTrue($date->isVisible());

        $contents = $page->find("css", ".contents");
        $this->assertTrue($contents->isVisible());
        #endregion


        //Contents that should be hidden when the page loads
        #region Conversation contents hidden
        //Id Based
        $jsonError = $page->find("css", "#jsonError");
        $this->assertFalse($jsonError->isVisible());

        $paginationHolder = $page->find("css", "#pagination-holder");
        $this->assertFalse($paginationHolder->isVisible());

        $prev = $page->find("css", "#prev");
        $this->assertFalse($prev->isVisible());

        $next = $page->find("css", "#next");
        $this->assertFalse($next->isVisible());

        $nextPage = $page->find("css", "#nextPage");
        $this->assertFalse($nextPage->isVisible());

        $prevPage = $page->find("css", "#prevPage");
        $this->assertFalse($prevPage->isVisible());

        //Class based

        $noConvoMsg = $page->find("css", ".noConvoMsg");
        $this->assertFalse($noConvoMsg->isVisible());

        $errorMsg = $page->find("css", ".errorMsg");
        $this->assertFalse($errorMsg->isVisible());

        $navLink = $page->find("css", ".navLink");
        $this->assertFalse($navLink->isVisible());

        $pagination = $page->find("css", ".pagination");
        $this->assertFalse($pagination->isVisible());

        $pageItem = $page->find("css", ".page-item");
        $this->assertFalse($pageItem->isVisible());
        #endregion

        $session->wait(1000);

        //Scroll the page to the bottom
        $session->executeScript('window.scrollTo(0,document.body.scrollHeight);');

        //Wait for the new page to load
        $session->wait(1000);

        //Checking the count after the first load
        $this->assertTrue(count($page->findAll("css", ".convoItm")) == 40, "Did not find 40 elements.");

        //Scroll the page to the bottom
        $session->executeScript('window.scrollTo(0,document.body.scrollHeight);');

        //Wait for the new page to load
        $session->wait(1000);

        //Checking the final count
        $this->assertTrue(count($page->findAll("css", ".convoItm")) == 50, "Did not find 40 elements.");

    }

    /*
     * Test to ensure that the page loads the first conversation that the user clicks on in desktop view
     */
    public function testViewsOneConversationDesktop()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Make sure this is a real user in the fixture
        $un = "messageViewerOne@gmail.com";
        $pw = "password";

        //log in with your credentials
        $session->setBasicAuth($un, $pw);

        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Navigate to the conversation page
        $navigate = $page->find("css", "#viewConversations")->click();

        //Wait for the new page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        //Contents that should be displayed when the page loads
        #region Conversation contents visable
        //ID based
        $mainWrapper = $page->find("css", "#mainWrapper");
        $this->assertTrue($mainWrapper->isVisible());

        $headerText = $page->find("css", "#headerText");
        $this->assertTrue($headerText->isVisible());

        $msgContainer = $page->find("css", "#msgContainer");
        $this->assertTrue($msgContainer->isVisible());

        $headings = $page->find("css", "#headings");
        $this->assertTrue($headings->isVisible());

        $convoBox = $page->find("css", "#convoBox");
        $this->assertTrue($convoBox->isVisible());

        //Class based
        $head = $page->find("css", ".head");
        $this->assertTrue($head->isVisible());

        $convoItm = $page->find("css", ".convoItm");
        $this->assertTrue($convoItm->isVisible());

        $read = $page->find("css", ".read");
        $this->assertTrue($read->isVisible());

        $sender = $page->find("css", ".sender");
        $this->assertTrue($sender->isVisible());

        $date = $page->find("css", ".date");
        $this->assertTrue($date->isVisible());

        $contents = $page->find("css", ".contents");
        $this->assertTrue($contents->isVisible());
        #endregion


        //Contents that should be hidden when the page loads
        #region Conversation contents hidden
        //Id Based
        $paginationHolder = $page->find("css", "#pagination-holder");
        $this->assertFalse($paginationHolder->isVisible());

        $prev = $page->find("css", "#prev");
        $this->assertFalse($prev->isVisible());

        $next = $page->find("css", "#next");
        $this->assertFalse($next->isVisible());

        $prevPage = $page->find("css", "#prevPage");
        $this->assertFalse($prevPage->isVisible());

        $nextPage = $page->find("css", "#nextPage");
        $this->assertFalse($nextPage->isVisible());

        $jsonError = $page->find("css", "#jsonError");
        $this->assertFalse($jsonError->isVisible());

        //Class based

        $noConvoMsg = $page->find("css", ".noConvoMsg");
        $this->assertFalse($noConvoMsg->isVisible());

        $errorMsg = $page->find("css", ".errorMsg");
        $this->assertFalse($errorMsg->isVisible());

        $pagination = $page->find("css", ".pagination");
        $this->assertFalse($pagination->isVisible());

        $pageItem = $page->find("css", ".page-item");
        $this->assertFalse($pageItem->isVisible());

        $navLink = $page->find("css", ".navLink");
        $this->assertFalse($navLink->isVisible());
        #endregion

        //Check that the values passed are correct
        $this->assertEquals($sender->getText(), 'Tony Stark'); //Ensure the text of the message sender is correct
        $this->assertEquals($date->getText(), '2250-02-05'); //Ensure the date is displayed correctly
        $this->assertEquals($contents->getText(), "Don't sell my shield while I'm gone");


        //Navigating to the page with one message
        $convoItems = $page->findAll("css", ".convoItm");
        $convoItems[1]->click();

        //Wait for the new page to load
        $session->wait(500);

        //Checking that the proper page elements are displayed
        #region Message contents visable
        //by ID
        $mainWrapper = $page->find("css", "#mainWrapper");
        $this->assertTrue($mainWrapper->isVisible());

        $headerText = $page->find("css", "#headerText");
        $this->assertTrue($headerText->isVisible());

        $msgContainer = $page->find("css", "#msgContainer");
        $this->assertTrue($msgContainer->isVisible());

        //by Class
        $msgBox = $page->find("css", ".msgBox");
        $this->assertTrue($msgBox->isVisible());

        $snglMsg = $page->find("css", ".snglMsg");
        $this->assertTrue($snglMsg->isVisible());

        $date = $page->find("css", ".date");
        $this->assertTrue($date->isVisible());

        $time = $page->find("css", ".time");
        $this->assertTrue($time->isVisible());

        $contents = $page->find("css", ".contents");
        $this->assertTrue($contents->isVisible());
        #endregion

        //Checking that the proper page elements are hidden
        #region Message contents hidden
        //By ID
        $jsonError = $page->find("css", "#jsonError");
        $this->assertFalse($jsonError->isVisible());

        //By class
        $errorMsg = $page->find("css", ".errorMsg");
        $this->assertFalse($errorMsg->isVisible());
        #endregion

        //Checking that the message contents display properly
        $this->assertEquals($date->gettext(), '2015-05-01', "Date was wrong");
        $this->assertEquals($time->gettext(), '16:00', "Time was wrong");
        $this->assertEquals($contents->gettext(), "Hulk go off world. bye", "Message contents was wrong");

    }

    /*
     * Test to ensure that the page loads the first conversation that the user clicks on in mobile  view
     */
    public function testViewsOneConversationMobile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Make sure this is a real user in the fixture
        $un = "messageViewerOne@gmail.com";
        $pw = "password";

        //log in with your credentials
        $session->setBasicAuth($un, $pw);

        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Navigate to the conversation page
        $navigate = $page->find("css", "#viewConversations")->click();

        //Resizing the window to go to mobile view
        $session->resizeWindow(700, 900, 'current');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        //Contents that should be displayed when the page loads
        #region Conversation contents visable
        //ID based
        $mainWrapper = $page->find("css", "#mainWrapper");
        $this->assertTrue($mainWrapper->isVisible());

        $headerText = $page->find("css", "#headerText");
        $this->assertTrue($headerText->isVisible());

        $msgContainer = $page->find("css", "#msgContainer");
        $this->assertTrue($msgContainer->isVisible());

        $headings = $page->find("css", "#headings");
        $this->assertTrue($headings->isVisible());

        $convoBox = $page->find("css", "#convoBox");
        $this->assertTrue($convoBox->isVisible());

        //Class based
        $head = $page->find("css", ".head");
        $this->assertTrue($head->isVisible());

        $convoItm = $page->find("css", ".convoItm");
        $this->assertTrue($convoItm->isVisible());

        $read = $page->find("css", ".read");
        $this->assertTrue($read->isVisible());

        $sender = $page->find("css", ".sender");
        $this->assertTrue($sender->isVisible());

        $date = $page->find("css", ".date");
        $this->assertTrue($date->isVisible());

        $contents = $page->find("css", ".contents");
        $this->assertTrue($contents->isVisible());
        #endregion


        //Contents that should be hidden when the page loads
        #region Conversation contents hidden
        //Id Based
        $paginationHolder = $page->find("css", "#pagination-holder");
        $this->assertFalse($paginationHolder->isVisible());

        $prev = $page->find("css", "#prev");
        $this->assertFalse($prev->isVisible());

        $next = $page->find("css", "#next");
        $this->assertFalse($next->isVisible());

        $prevPage = $page->find("css", "#prevPage");
        $this->assertFalse($prevPage->isVisible());

        $nextPage = $page->find("css", "#nextPage");
        $this->assertFalse($nextPage->isVisible());

        $jsonError = $page->find("css", "#jsonError");
        $this->assertFalse($jsonError->isVisible());

        //Class based

        $noConvoMsg = $page->find("css", ".noConvoMsg");
        $this->assertFalse($noConvoMsg->isVisible());

        $errorMsg = $page->find("css", ".errorMsg");
        $this->assertFalse($errorMsg->isVisible());

        $pagination = $page->find("css", ".pagination");
        $this->assertFalse($pagination->isVisible());

        $pageItem = $page->find("css", ".page-item");
        $this->assertFalse($pageItem->isVisible());

        $navLink = $page->find("css", ".navLink");
        $this->assertFalse($navLink->isVisible());
        #endregion

        //Check that the values passed are correct
        $this->assertEquals($sender->getText(), 'Tony Stark'); //Ensure the text of the message sender is correct
        $this->assertEquals($date->getText(), '2250-02-05'); //Ensure the date is displayed correctly
        $this->assertEquals($contents->getText(), "Don't sell my shield while I'm gone");


        //Navigating to the page with one message
        $convoItems = $page->findAll("css", ".convoItm");
        $convoItems[1]->click();

        //Wait for page to load
        $session->wait(1000);

        //Resizing the window to go to mobile view
        //$session->resizeWindow(700, 900, 'current');

        //Checking that the proper page elements are displayed
        #region Message contents visable
        //by ID
        $mainWrapper = $page->find("css", "#mainWrapper");
        $this->assertTrue($mainWrapper->isVisible());

        $headerText = $page->find("css", "#headerText");
        $this->assertTrue($headerText->isVisible());

        $msgContainer = $page->find("css", "#msgContainer");
        $this->assertTrue($msgContainer->isVisible());

        //by Class
        $msgBox = $page->find("css", ".msgBox");
        $this->assertTrue($msgBox->isVisible());

        $snglMsg = $page->find("css", ".snglMsg");
        $this->assertTrue($snglMsg->isVisible());

        $date = $page->find("css", ".date");
        $this->assertTrue($date->isVisible());

        $time = $page->find("css", ".time");
        $this->assertTrue($time->isVisible());

        $contents = $page->find("css", ".contents");
        $this->assertTrue($contents->isVisible());
        #endregion

        //Checking that the proper page elements are hidden
        #region Message contents hidden
        //By ID
        $jsonError = $page->find("css", "#jsonError");
        $this->assertFalse($jsonError->isVisible());

        //By class
        $errorMsg = $page->find("css", ".errorMsg");
        $this->assertFalse($errorMsg->isVisible());
        #endregion

        //Checking that the message contents display properly
        $this->assertEquals($date->gettext(), '2015-05-01', "Date was wrong");
        $this->assertEquals($time->gettext(), '16:00', "Time was wrong");
        $this->assertEquals($contents->gettext(), "Hulk go off world. bye", "Message contents was wrong");
    }

    /*
     * Test to ensure that the user has 250 messages inside of the current conversation in desktop view
     */
    public function testView250MessagesDesktop()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Make sure this is a real user in the fixture
        $un = "messageViewerThree@gmail.com";
        $pw = "password";

        //log in with your credentials
        $session->setBasicAuth($un, $pw);

        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Navigate to the conversation page
        $navigate = $page->find("css", "#viewConversations")->click();

        //Wait for the new page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        //Contents that should be displayed when the page loads
        #region Conversation contents visable
        //ID based
        $mainWrapper = $page->find("css", "#mainWrapper");
        $this->assertTrue($mainWrapper->isVisible());

        $headerText = $page->find("css", "#headerText");
        $this->assertTrue($headerText->isVisible());

        $msgContainer = $page->find("css", "#msgContainer");
        $this->assertTrue($msgContainer->isVisible());

        $headings = $page->find("css", "#headings");
        $this->assertTrue($headings->isVisible());

        $convoBox = $page->find("css", "#convoBox");
        $this->assertTrue($convoBox->isVisible());

        //Class based
        $head = $page->find("css", ".head");
        $this->assertTrue($head->isVisible());

        $convoItm = $page->find("css", ".convoItm");
        $this->assertTrue($convoItm->isVisible());

        $read = $page->find("css", ".read");
        $this->assertTrue($read->isVisible());

        $sender = $page->find("css", ".sender");
        $this->assertTrue($sender->isVisible());

        $date = $page->find("css", ".date");
        $this->assertTrue($date->isVisible());

        $contents = $page->find("css", ".contents");
        $this->assertTrue($contents->isVisible());
        #endregion


        //Contents that should be hidden when the page loads
        #region Conversation contents hidden
        //Id Based
        $paginationHolder = $page->find("css", "#pagination-holder");
        $this->assertFalse($paginationHolder->isVisible());

        $prev = $page->find("css", "#prev");
        $this->assertFalse($prev->isVisible());

        $next = $page->find("css", "#next");
        $this->assertFalse($next->isVisible());

        $prevPage = $page->find("css", "#prevPage");
        $this->assertFalse($prevPage->isVisible());

        $nextPage = $page->find("css", "#nextPage");
        $this->assertFalse($nextPage->isVisible());

        $jsonError = $page->find("css", "#jsonError");
        $this->assertFalse($jsonError->isVisible());

        //Class based

        $noConvoMsg = $page->find("css", ".noConvoMsg");
        $this->assertFalse($noConvoMsg->isVisible());

        $errorMsg = $page->find("css", ".errorMsg");
        $this->assertFalse($errorMsg->isVisible());

        $pagination = $page->find("css", ".pagination");
        $this->assertFalse($pagination->isVisible());

        $pageItem = $page->find("css", ".page-item");
        $this->assertFalse($pageItem->isVisible());

        $navLink = $page->find("css", ".navLink");
        $this->assertFalse($navLink->isVisible());
        #endregion

        //Check that the values passed are correct
        $this->assertEquals($sender->getText(), 'Steve Rogers'); //Ensure the text of the message sender is correct
        $this->assertEquals($date->getText(), '2250-02-05'); //Ensure the date is displayed correctly
        $this->assertEquals($contents->getText(), "Don't sell my shield while I'm gone");

        //Navigating to the page with one message
        $convoItm->click();

        //Wait for the new page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(2000);
        //Checking that the proper page elements are displayed
        #region Message contents visible
        //by ID
        $mainWrapper = $page->find("css", "#mainWrapper");
        $this->assertTrue($mainWrapper->isVisible());

        $headerText = $page->find("css", "#headerText");
        $this->assertTrue($headerText->isVisible());

        $msgContainer = $page->find("css", "#msgContainer");
        $this->assertTrue($msgContainer->isVisible());

        //by Class
        $msgBox = $page->find("css", ".msgBox");
        $this->assertTrue($msgBox->isVisible());

        $snglMsg = $page->find("css", ".snglMsg");
        $this->assertTrue($snglMsg->isVisible());

        $date = $page->find("css", ".date");
        $this->assertTrue($date->isVisible());

        $time = $page->find("css", ".time");
        $this->assertTrue($time->isVisible());

        $contents = $page->find("css", ".contents");
        $this->assertTrue($contents->isVisible());
        #endregion

        //Checking that the proper page elements are hidden
        #region Message contents hidden
        //By ID
        $jsonError = $page->find("css", "#jsonError");
        $this->assertFalse($jsonError->isVisible());

        //By class
        $errorMsg = $page->find("css", ".errorMsg");
        $this->assertFalse($errorMsg->isVisible());
        #endregion



        for ($i = 0; $i <= 25; $i++)
        {
            $session->executeScript("document.getElementsByClassName('msgBox')[0].scrollTop = 0");
            $session->wait(100);
        }


        $totalMsgs = count($page->findAll("css", ".snglMsg"));

        //Check that the amount of loaded items is correct for how much has loaded
        $this->assertTrue($totalMsgs == 250);

    }

    /*
     * Test to ensure that the user has 250 messages inside of the current conversation in desktop view
     */
    public function testView250MessagesMobile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Make sure this is a real user in the fixture
        $un = "messageViewerThree@gmail.com";
        $pw = "password";

        //log in with your credentials
        $session->setBasicAuth($un, $pw);

        //Visit the page navigate to "home page" and click the link that leads to our page.
        //This link needs to be updated in all tests
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Navigate to the conversation page
        $navigate = $page->find("css", "#viewConversations")->click();

        //Resizing the window to go to mobile view
        $session->resizeWindow(700, 900, 'current');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        //Contents that should be displayed when the page loads
        #region Conversation contents visable
        //ID based
        $mainWrapper = $page->find("css", "#mainWrapper");
        $this->assertTrue($mainWrapper->isVisible());

        $headerText = $page->find("css", "#headerText");
        $this->assertTrue($headerText->isVisible());

        $msgContainer = $page->find("css", "#msgContainer");
        $this->assertTrue($msgContainer->isVisible());

        $headings = $page->find("css", "#headings");
        $this->assertTrue($headings->isVisible());

        $convoBox = $page->find("css", "#convoBox");
        $this->assertTrue($convoBox->isVisible());

        //Class based
        $head = $page->find("css", ".head");
        $this->assertTrue($head->isVisible());

        $convoItm = $page->find("css", ".convoItm");
        $this->assertTrue($convoItm->isVisible());

        $read = $page->find("css", ".read");
        $this->assertTrue($read->isVisible());

        $sender = $page->find("css", ".sender");
        $this->assertTrue($sender->isVisible());

        $date = $page->find("css", ".date");
        $this->assertTrue($date->isVisible());

        $contents = $page->find("css", ".contents");
        $this->assertTrue($contents->isVisible());
        #endregion


        //Contents that should be hidden when the page loads
        #region Conversation contents hidden
        //Id Based
        $paginationHolder = $page->find("css", "#pagination-holder");
        $this->assertFalse($paginationHolder->isVisible());

        $prev = $page->find("css", "#prev");
        $this->assertFalse($prev->isVisible());

        $next = $page->find("css", "#next");
        $this->assertFalse($next->isVisible());

        $prevPage = $page->find("css", "#prevPage");
        $this->assertFalse($prevPage->isVisible());

        $nextPage = $page->find("css", "#nextPage");
        $this->assertFalse($nextPage->isVisible());

        $jsonError = $page->find("css", "#jsonError");
        $this->assertFalse($jsonError->isVisible());

        //Class based

        $noConvoMsg = $page->find("css", ".noConvoMsg");
        $this->assertFalse($noConvoMsg->isVisible());

        $errorMsg = $page->find("css", ".errorMsg");
        $this->assertFalse($errorMsg->isVisible());

        $pagination = $page->find("css", ".pagination");
        $this->assertFalse($pagination->isVisible());

        $pageItem = $page->find("css", ".page-item");
        $this->assertFalse($pageItem->isVisible());

        $navLink = $page->find("css", ".navLink");
        $this->assertFalse($navLink->isVisible());
        #endregion

        //Check that the values passed are correct
        $this->assertEquals($sender->getText(), 'Steve Rogers'); //Ensure the text of the message sender is correct
        $this->assertEquals($date->getText(), '2250-02-05'); //Ensure the date is displayed correctly
        $this->assertEquals($contents->getText(), "Don't sell my shield while I'm gone");

        //Navigating to the page with one message
        $convoItm->click();

        //Wait for the new page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(2000);
        //Checking that the proper page elements are displayed
        #region Message contents visible
        //by ID
        $mainWrapper = $page->find("css", "#mainWrapper");
        $this->assertTrue($mainWrapper->isVisible());

        $headerText = $page->find("css", "#headerText");
        $this->assertTrue($headerText->isVisible());

        $msgContainer = $page->find("css", "#msgContainer");
        $this->assertTrue($msgContainer->isVisible());

        //by Class
        $msgBox = $page->find("css", ".msgBox");
        $this->assertTrue($msgBox->isVisible());

        $snglMsg = $page->find("css", ".snglMsg");
        $this->assertTrue($snglMsg->isVisible());

        $date = $page->find("css", ".date");
        $this->assertTrue($date->isVisible());

        $time = $page->find("css", ".time");
        $this->assertTrue($time->isVisible());

        $contents = $page->find("css", ".contents");
        $this->assertTrue($contents->isVisible());
        #endregion

        //Checking that the proper page elements are hidden
        #region Message contents hidden
        //By ID
        $jsonError = $page->find("css", "#jsonError");
        $this->assertFalse($jsonError->isVisible());

        //By class
        $errorMsg = $page->find("css", ".errorMsg");
        $this->assertFalse($errorMsg->isVisible());
        #endregion



        for ($i = 0; $i <= 30; $i++)
        {
            $session->executeScript("document.getElementsByClassName('msgBox')[0].scrollTop = 0");
            $session->wait(100);
        }


        $totalMsgs = count($page->findAll("css", ".snglMsg"));

        //Check that the amount of loaded items is correct for how much has loaded
        $this->assertTrue($totalMsgs == 250);
    }



    /**
     * deletes the created user and tears down the database connection
     */
    protected function tearDown()
    {
        $fixture = new ViewForMessagesFixture();
        $fixture->unload($this->em);

        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}