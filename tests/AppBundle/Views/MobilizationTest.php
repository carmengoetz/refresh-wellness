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
use AppBundle\DataFixtures\ViewWPFixtures;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
//Step 1:
use Tests\AppBundle\DatabasePrimer;

/**
 * Unit testing for nearby wellness professional front end
 *
 * @version 1.0
 * @author cst231
 */
class MobilizationTest extends WebTestCase
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
        //if need to test indv unittests using testmanager, uncomment code below
        //$_SERVER['KERNEL_DIR'] = './app';

        //Step 2:

        self::bootKernel();

        //Apply the primer
        DatabasePrimer::prime(self::$kernel);

        //Set entity manager
        $this->em = DatabasePrimer::$entityManager;

        //$fixture = new ViewWPFixtures();
        //$fixture->load($this->em);
        //$fixture->loadWP($this->em);

        $this->fixture = new ViewAggregateStatsFixtures();
        $this->fixture->load($this->em);


        $this->driver = new ChromeDriver('http://localhost:9222', null, 'http://127.0.0.1:80');
        $this->mink = new Mink(array('browser' => new Session(new ChromeDriver('http://127.0.0.1:9222', null, 'http://127.0.0.1:80'))));

        $this->mink->setDefaultSessionName('browser');
    }


    /**
     * Test to ensure that the registration page looks good on mobile
     */
    public function testViewRegistrationMobile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Set the screen size
        $session->resizeWindow(900, 800, 'current');

        //step 3:
        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/register');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Check to make sure the welcome image is visible
        $this->assertTrue(!$page->find('css', '#splash_image')->isVisible());
        $this->assertTrue(!$page->find('css', '#desktop_logo')->isVisible());
        $this->assertTrue($page->find('css', '#mobile_logo')->isVisible());



    }

    /**
     * Test to ensure that the registration page looks good on desktop
     */
    public function testViewRegistrationDesktop()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Set the screen size
        $session->resizeWindow(1100, 800, 'current');

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/register');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Check to make sure the welcome image is visible
        $this->assertTrue($page->find('css', '#splash_image')->isVisible());
        $this->assertTrue($page->find('css', '#desktop_logo')->isVisible());
        $this->assertTrue(!$page->find('css', '#mobile_logo')->isVisible());
    }

    /**
     * Test to ensure that the individual stats page looks good on mobile
     */
    public function testViewIndividualStatsMobile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "email1@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Set the screen size
        $session->resizeWindow(900, 800, 'current');

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/me');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Check to make sure the nav menu is visible
        $this->assertTrue(!$page->find('css', '#wrapper')->hasClass("toggled"));


        //Images
        $this->assertTrue($page->find('css', '#brand')->hasClass("brand-mobile"));
        $this->assertTrue(!$page->find('css', '#brand')->hasClass("brand-desktop"));


    }

    /**
     * Test to ensure that the individual stats page looks good on desktop
     */
    public function testViewIndividualStatsDesktop()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "email1@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Set the screen size
        $session->resizeWindow(1100, 800, 'current');

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/me');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Check to make sure the nav menu is visible
        $this->assertTrue($page->find('css', '#wrapper')->hasClass("toggled"));


        //Images
        $this->assertTrue(!$page->find('css', '#brand')->hasClass("brand-mobile"));
        $this->assertTrue($page->find('css', '#brand')->hasClass("brand-desktop"));
    }

    /**
     * Test to ensure that the aggregate stats page looks good on mobile
     */
    public function testViewAggregateStatsMobile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "emailWellProf@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Set the screen size
        $session->resizeWindow(900, 800, 'current');

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/patientAll');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Check to make sure the nav menu is visible
        $this->assertTrue(!$page->find('css', '#wrapper')->hasClass("toggled"));


        //Images
        $this->assertTrue($page->find('css', '#brand')->hasClass("brand-mobile"));
        $this->assertTrue(!$page->find('css', '#brand')->hasClass("brand-desktop"));
    }

    /**
     * Test to ensure that the aggregate stats page looks good on desktop
     */
    public function testViewAggregateStatsDesktop()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "emailWellProf@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Set the screen size
        $session->resizeWindow(1100, 800, 'current');

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/patientAll');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Check to make sure the nav menu is visible
        $this->assertTrue($page->find('css', '#wrapper')->hasClass("toggled"));


        //Images
        $this->assertTrue(!$page->find('css', '#brand')->hasClass("brand-mobile"));
        $this->assertTrue($page->find('css', '#brand')->hasClass("brand-desktop"));
    }

    /**
     * Test to ensure that the nearby WP page looks good on mobile
     */
    public function testViewNearbyWPMobile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "email1@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Set the screen size
        $session->resizeWindow(900, 800, 'current');

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Check to make sure the nav menu is visible
        $this->assertTrue(!$page->find('css', '#wrapper')->hasClass("toggled"));


        //Images
        $this->assertTrue($page->find('css', '#brand')->hasClass("brand-mobile"));
        $this->assertTrue(!$page->find('css', '#brand')->hasClass("brand-desktop"));


        $this->assertTrue(!$page->find('css', '.map')->isVisible());

        $this->assertTrue($page->find('css', '.showmap')->isVisible());

        $page->find('css', '.showmap')->click();

        $this->assertTrue($page->find('css', '.map')->isVisible());

        $this->assertEquals(10,count($page->findAll('css','.wellnessPro')));

        //Scroll down
        $session->executeScript("window.scrollTo(0,document.body.scrollHeight)");

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(2000);

        $this->assertTrue(10 < count($page->findAll('css','.wellnessPro')));


    }

    /**
     * Test to ensure that the nearby WP page looks good on desktop
     */
    public function testViewNearbyWPDesktop()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "email1@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Set the screen size
        $session->resizeWindow(1100, 800, 'current');

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Check to make sure the nav menu is visible
        $this->assertTrue($page->find('css', '#wrapper')->hasClass("toggled"));


        //Images
        $this->assertTrue(!$page->find('css', '#brand')->hasClass("brand-mobile"));
        $this->assertTrue($page->find('css', '#brand')->hasClass("brand-desktop"));


        $this->assertTrue($page->find('css', '.map')->isVisible());
        $this->assertTrue(!$page->find('css', '.showmap')->isVisible());
    }

    /**
     * Tests to ensure the nav links exist on all pages on desktop
     */
    public function testForNavLinksDesktop()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "email1@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Set the screen size
        $session->resizeWindow(1100, 800, 'current');

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Check that the nav links exist
        $this->assertTrue($page->find('css', '#myStats_link') != null);
        $this->assertTrue($page->find('css', '#nearbyWP_link') != null);
        $this->assertTrue($page->find('css', '#logout_link') != null);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/patientAll');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Check that the nav links exist
        $this->assertTrue($page->find('css', '#myStats_link') != null);
        $this->assertTrue($page->find('css', '#nearbyWP_link') != null);
        $this->assertTrue($page->find('css', '#logout_link') != null);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/stats/view/me');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Check that the nav links exist
        $this->assertTrue($page->find('css', '#myStats_link') != null);
        $this->assertTrue($page->find('css', '#nearbyWP_link') != null);
        $this->assertTrue($page->find('css', '#logout_link') != null);


    }

    /**
     * Tests to ensure the nav links exist on all pages in mobile
     */
    public function testForNavLinksMobile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "email1@email.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //Set the screen size
        $session->resizeWindow(900, 800, 'current');

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Check that the nav links exist
        $this->assertFalse($page->find('css', '#wrapper')->hasClass('toggled'));

        $page->find('css', '#menu-toggle')->click();
        //Check that the nav links exist
        $this->assertTrue($page->find('css', '#wrapper')->hasClass('toggled'));



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