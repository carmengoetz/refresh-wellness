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
use AppBundle\DataFixtures\WellnessChartsFixtures;
use AppBundle\DataFixtures\ViewAggregateStatsFixtures;
use Tests\AppBundle\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
/**
 * Unit testing for nearby wellness professional front end
 *
 * @version 1.0
 * @author cst231
 */
class ViewAggregateStatsTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     * @var mixed
     */
    private $em;
    private $driver;
    private $mink;
    private $fixture;
    /**
     * sets up the tests
     */
    protected function setUp()
    {
        self::bootKernel();

        //Apply the primer
        DatabasePrimer::prime(self::$kernel);

        //Set entity manager
        $this->em = DatabasePrimer::$entityManager;

        $this->fixture = new ViewAggregateStatsFixtures();
        $this->fixture->load($this->em);

        $this->driver = new ChromeDriver('http://localhost:9222', null, 'http://127.0.0.1:80');
        $this->mink = new Mink(array('browser' => new Session(new ChromeDriver('http://127.0.0.1:9222', null, 'http://127.0.0.1:80'))));

        $this->mink->setDefaultSessionName('browser');
    }


    /**
     * Test to ensure that the page loads your patients' stats correctly.
     */
    public function testViewAggregateStatsPatient()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $un = "emailWellProf@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/patientAll');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Ensure that we get your patient's stats
        $chartHeader = $page->find("css", ".chartheader");
        $this->assertTrue($chartHeader->isVisible());

        $chartContain = $page->find("css", ".chart-container");
        $this->assertTrue($chartContain->isVisible());

        $usercount = $page->find("css", ".total");
        $this->assertTrue($usercount->getText() === "10 patients");

    }

    /**
     * Test to ensure that the page loads your org member's stats correctly.
     */
    public function testViewAggregateStatsOrgMember()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $un = "emailOrgAdmin@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);
        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/orgMemberAll');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Ensure that we get your org member's stats
        $chartHeader = $page->find("css", ".chartheader");
        $this->assertTrue($chartHeader->isVisible());

        $chartContain = $page->find("css", ".chart-container");
        $this->assertTrue($chartContain->isVisible());

        $usercount = $page->find("css", ".total");
        $this->assertTrue($usercount->getText() === "90 members");

    }

    /**
     * Test to ensure that the page loads your patient's stats correctly.
     */
    public function testViewAggregateStatsPatientNone()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $un = "emailWellProfNoPatients@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/patientAll');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Ensure that we get your patient's stats
        $chartHeader = $page->find("css", ".chartheader");
        $this->assertTrue(!$chartHeader->isVisible());

        $chartContain = $page->find("css", ".chart-container");
        $this->assertTrue(!$chartContain->isVisible());

        $errormsg = $page->find("css", ".errorMsg");
        $this->assertTrue($errormsg->getText() === "Sorry, no statistics available to view.");

    }

    /**
     * Test to ensure that the page loads your org member's stats correctly.
     */
    public function testViewAggregateStatsOrgMemberNone()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $un = "emailOrgAdminNoMembers@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/orgMemberAll');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Ensure that we get your org member's stats
        $chartHeader = $page->find("css", ".chartheader");
        $this->assertTrue(!$chartHeader->isVisible());

        $chartContain = $page->find("css", ".chart-container");
        $this->assertTrue(!$chartContain->isVisible());

        $errormsg = $page->find("css", ".errorMsg");
        $this->assertTrue($errormsg->getText() === "Sorry, no statistics available to view.");

    }
    /**
     * Test to ensure that the user can't view stats if not logged in
     */
    //public function testWPNotLoggedIn()
    //{
    //    //Get a local copy of the session
    //    $session = $this->mink->getSession();

    //    $session->visit('http://127.0.0.1:80/app_test.php/authenticate/logout');

    //    //Visit the page
    //    $session->visit('http://127.0.0.1:80/app_test.php/stats/view/patientAll');

    //    //Wait for page to load
    //    $session->wait(60000, '(0 === jQuery.active)');

    //    //Get the page node
    //    $page = $session->getPage();

    //    //Ensure that no stats are returned
    //    $chartHeader = $page->find("css", ".chartheader");
    //    $this->assertTrue(!$chartHeader->isVisible());

    //    $chartContain = $page->find("css", ".chart-container");
    //    $this->assertTrue(!$chartContain->isVisible());

    //    //Ensure that the error message is displayed with the appropriate message
    //    $errorDisplay = $page->find("css", ".errorMsg");
    //    $this->assertTrue($errorDisplay->isVisible());
    //    $this->assertTrue($errorDisplay->getText() == 'User is not logged in.');
    //}

    /**
     * Test to ensure that the user can't view stats if not logged in
     */
    //public function testOANotLoggedIn()
    //{
    //    //Get a local copy of the session
    //    $session = $this->mink->getSession();

    //    $session->visit('http://127.0.0.1:80/app_test.php/authenticate/logout');

    //    //Visit the page
    //    $session->visit('http://127.0.0.1:80/app_test.php/stats/view/orgMemberAll');

    //    //Wait for page to load
    //    $session->wait(60000, '(0 === jQuery.active)');

    //    //Get the page node
    //    $page = $session->getPage();

    //    //Ensure that no stats are returned
    //    $chartHeader = $page->find("css", ".chartheader");
    //    $this->assertTrue(!$chartHeader->isVisible());

    //    $chartContain = $page->find("css", ".chart-container");
    //    $this->assertTrue(!$chartContain->isVisible());

    //    //Ensure that the error message is displayed with the appropriate message
    //    $errorDisplay = $page->find("css", ".errorMsg");
    //    $this->assertTrue($errorDisplay->isVisible());
    //    $this->assertTrue($errorDisplay->getText() == 'User is not logged in.');
    //}

    /**
     * Ensure that a user cannot view stats of someone they don't have the correct
     * relationship with
     */
    public function testOANotAuthorized()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $un = "emailWellProf@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/orgMemberAll');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Ensure that no stats are returned
        $chartHeader = $page->find("css", ".chartheader");
        $this->assertTrue(!$chartHeader->isVisible());

        $chartContain = $page->find("css", ".chart-container");
        $this->assertTrue(!$chartContain->isVisible());

        //Ensure that the error message is displayed with the appropriate message
        $errorDisplay = $page->find("css", ".errorMsg");
        $this->assertTrue($errorDisplay->isVisible());
        $this->assertTrue($errorDisplay->getText() == "You do not have the correct permissions to view this organization's stats.");
    }



    /**
     * deletes the created user and tears down the database connection
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
}