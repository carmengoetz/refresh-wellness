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
use Tests\AppBundle\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Unit testing for nearby wellness professional front end
 *
 * @version 1.0
 * @author cst231
 */
class ViewIndividualStatsTest extends WebTestCase
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

        $this->fixture = new WellnessChartsFixtures();
        $this->fixture->load($this->em);

        $this->driver = new ChromeDriver('http://localhost:9222', null, 'http://127.0.0.1:80');
        $this->mink = new Mink(array('browser' => new Session(new ChromeDriver('http://127.0.0.1:9222', null, 'http://127.0.0.1:80'))));

        $this->mink->setDefaultSessionName('browser');
    }

    /**
     * Test to ensure that the page loads your own stats correctly.
     */
    public function testViewIndividualStatsMe()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $un = "cst.project5.refresh+wellnessCharts1@gmail.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/me');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Ensure that we get your own stats
        $chartHeader = $page->find("css", ".chartheader");
        $this->assertTrue($chartHeader->isVisible());

        $chartContain = $page->find("css", ".chart-container");
        $this->assertTrue($chartContain->isVisible());

    }

    /**
     * Test to ensure that the page loads your supportee's stats correctly.
     */
    public function testViewIndividualStatsSupportee()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $un = "cst.project5.refresh+wellnessCharts2@gmail.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/supportee=4WellnessCharts1');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Ensure that we get your supportee's stats
        $chartHeader = $page->find("css", ".chartheader");
        $this->assertTrue($chartHeader->isVisible());

        $chartContain = $page->find("css", ".chart-container");
        $this->assertTrue($chartContain->isVisible());

    }

    /**
     * Test to ensure that the page loads your patient's stats correctly.
     */
    public function testViewIndividualStatsPatient()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $un = "cst.project5.refresh+wellnessCharts3@gmail.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/patient=4WellnessCharts1');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Ensure that we get your patient's stats
        $chartHeader = $page->find("css", ".chartheader");
        $this->assertTrue($chartHeader->isVisible());

        $chartContain = $page->find("css", ".chart-container");
        $this->assertTrue($chartContain->isVisible());

    }

    /**
     * Test to ensure that the page loads your org member's stats correctly.
     */
    public function testViewIndividualStatsOrgMember()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $un = "cst.project5.refresh+wellnessCharts4@gmail.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/orgMember=4WellnessCharts1&group=groupForCharts');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Ensure that we get your org member's stats
        $chartHeader = $page->find("css", ".chartheader");
        $this->assertTrue($chartHeader->isVisible());

        $chartContain = $page->find("css", ".chart-container");
        $this->assertTrue($chartContain->isVisible());

    }

    /**
     * Test to ensure that the page loads your own stats correctly when you have some data points of 0.
     */
    public function testViewIndividualStatsWithZero()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $un = "cst.project5.refresh+wellnessCharts1@gmail.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/me');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Ensure that we get your stats OK
        $chartHeader = $page->find("css", ".chartheader");
        $this->assertTrue($chartHeader->isVisible());

        $chartContain = $page->find("css", ".chart-container");
        $this->assertTrue($chartContain->isVisible());

    }

    /**
     * Test to ensure that the page loads your own stats correctly when you have some data points of 10.
     */
    public function testViewIndividualStatsWith10()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $un = "cst.project5.refresh+wellnessCharts1@gmail.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/me');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Ensure that we get your stats OK
        $chartHeader = $page->find("css", ".chartheader");
        $this->assertTrue($chartHeader->isVisible());

        $chartContain = $page->find("css", ".chart-container");
        $this->assertTrue($chartContain->isVisible());

    }

    /**
     * Test to ensure that the appropriate error message is displayed when an invalid user is passed in
     */
    public function testUserDoesntExists()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $un = "cst.project5.refresh+wellnessCharts2@gmail.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/supportee=4WellnessChartsNobody1');

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
        $this->assertTrue($errorDisplay->getText() == 'The user specified does not exist.');
    }

    /**
     * Test to ensure that the user can't view stats if not logged in
     */
    //public function testUserNotLoggedIn()
    //{
    //    //Get a local copy of the session
    //    $session = $this->mink->getSession();

    //    $session->visit('http://127.0.0.1:80/app_test.php/authenticate/logout');

    //    //Visit the page
    //    $session->visit('http://127.0.0.1:80/app_test.php/stats/view/supportee=4WellnessCharts1');

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
    public function testUserNotAuthorized()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $un = "cst.project5.refresh+wellnessCharts2@gmail.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/patient=4WellnessCharts2');

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
        $this->assertTrue($errorDisplay->getText() == 'You do not have the correct permissions to view this users stats.');
    }

    /**
     * Ensure that the page displays correctly when the user has no stats to view
     */
    public function testNoStatsToView()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $un = "cst.project5.refresh+wellnessCharts3@gmail.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/patient=4WellnessCharts2');

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
        $this->assertTrue($errorDisplay->getText() == 'Sorry, no statistics available to view');


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