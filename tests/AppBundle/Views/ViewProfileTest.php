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
use AppBundle\DataFixtures\ProfileFixtures;
//Step 1:
use Tests\AppBundle\DatabasePrimer;

/**
 * Unit testing for nearby wellness professional front end
 *
 * @version 1.0
 * @author cst231
 */
class ViewProfileTest extends WebTestCase
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

        $this->fixture = new ProfileFixtures();
        $this->fixture->load($this->em);


        $this->driver = new ChromeDriver('http://localhost:9222', null, 'http://127.0.0.1:80');
        $this->mink = new Mink(array('browser' => new Session(new ChromeDriver('http://127.0.0.1:9222', null, 'http://127.0.0.1:80'))));

        $this->mink->setDefaultSessionName('browser');
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

    /**
     * Test to ensure a user can get their own profile
     */
    public function testGetOwnProfile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'profileBucky@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/profile');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals('Bucky Barnes', $page->find('css', '#fullname')->getText());

    }

    /**
     *  test to ensure a not-logged in user gets the right error
     *  now reroutes
     */
    //public function testGetProfileNotLoggedIn()
    //{
    //    //Get a local copy of the session
    //    $session = $this->mink->getSession();

    //    $session->visit('http://127.0.0.1:80/app_test.php/authenticate/logout');

    //    //Visit the page
    //    $session->visit('http://127.0.0.1/app_test.php/profile');

    //    //Wait for page to load
    //    $session->wait(60000, '(0 === jQuery.active)');

    //    //Get the page node
    //    $page = $session->getPage();

    //    $this->assertEquals(false, $page->find('css', '.jsonError')->isVisible());
    //    $this->assertEquals('You are not logged in.', $page->find('css', '#fullname')->getText());
    //}

    /**
     *  test to ensure a user looking at another user's page loads correclty
     */
    public function testGetProfileOtherUserNoRel()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'profileBucky@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/profile/steve-rogers');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals('Steve Rogers', $page->find('css', '#fullname')->getText());
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop ')->isVisible());
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .add-friend')->hasAttribute('disabled'));
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .add-supporter')->hasAttribute('disabled'));
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .add-supportee')->hasAttribute('disabled'));
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .view-stats')->isVisible());

    }


    /**
     *  Test to ensure a user looking at a friend's page loads correctly
     */
    public function testGetProfileOtherUserFriends()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'profileBucky@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/profile/bruce-banner');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals('Bruce Banner', $page->find('css', '#fullname')->getText());
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop ')->isVisible());
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop .add-friend')->hasAttribute('disabled'));

    }

    /**
     *  Test to ensure a user looking at a supporter page loads correctly
     */
    public function testGetProfileOtherUserSupporter()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'profileBucky@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/profile/tony-stark');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals('Tony Stark', $page->find('css', '#fullname')->getText());
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop')->isVisible());
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop .add-supportee')->hasAttribute('disabled'));
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop .view-stats')->isVisible());
    }

    /**
     *  test to ensure a respondent looking at a WP page with no relation loads correctly
     */
    public function testGetProfileWPNoRel()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'profileBucky@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/profile/natasha-romanov');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals('Natasha Romanov', $page->find('css', '#fullname')->getText());
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop')->isVisible());
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .add-supporter')->isVisible());
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .add-supportee')->isVisible());
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop .add-wp')->isVisible());
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .view-stats')->isVisible());
    }

    /**
     *  Test to ensure a respondent looking at a WP page loads correctly
     */
    public function testGetProfileWPPatientRel()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'profileBucky@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/profile/vision-jones');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals('Vision Jones', $page->find('css', '#fullname')->getText());
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop ')->isVisible());
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .add-supporter')->isVisible());
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .add-supportee')->isVisible());
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop .add-wp')->isVisible());
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop .add-wp')->hasAttribute('disabled'));
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .view-stats')->isVisible());
    }

    /**
     *  test to ensure a user looking at another user's page with no relationship looks correct
     */
    public function testWPGetProfileOtherUserNoRel()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'profileNatasha@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/profile/bucky-barnes');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals('Bucky Barnes', $page->find('css', '#fullname')->getText());
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop ')->isVisible());
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .add-supporter')->isVisible());
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .add-supportee')->isVisible());
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop .add-patient')->isVisible());
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .add-patient')->hasAttribute('disabled'));
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .view-stats')->isVisible());
    }

    /**
     *  Test to ensure a respondent looking at a wellness pro page loads correctly
     */
    public function testWPGetProfileOtherUserPatientRel()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'profileVision@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/profile/bucky-barnes');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals('Bucky Barnes', $page->find('css', '#fullname')->getText());
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop ')->isVisible());
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .add-supporter')->isVisible());
        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .add-supportee')->isVisible());
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop .add-patient')->isVisible());
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop .add-patient')->hasAttribute('disabled'));
        $this->assertEquals(true, $page->find('css', '#control-panel-desktop .view-stats')->isVisible());
    }

    /**
     *  Test to ensure loading a profile for a non-existant user works correctly
     */
    public function testGetProfileUserNotExist()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'profileBucky@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/profile/thor');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals('User does not exist.', $page->find('css', '#fullname')->getText());
    }

    /**
     *  Test to make sure the link to the profile page appears
     */
    public function testProfileLinks()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'profileBucky@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/profile');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals(true, $page->find('css', '#my-profile_link')->isVisible());
    }

    /**
     *  Test to ensure the same profile image loads for the same user no matter what
     */
    public function testProfileImage()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'profileBucky@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/profile');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $profileURL = $page->find('css', '#profile-image')->getAttribute('src');

        //Logout
        $session->visit('http://127.0.0.1:80/app_test.php/authenticate/logout');

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/profile');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals($profileURL, $page->find('css', '#profile-image')->getAttribute('src'));
    }

    /**
     * Test to ensure all buttons work as expected
     */
    public function testButtonFunctionalityDesktop()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'profileBucky@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/profile/steve-rogers');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $numFriends = $page->find('css', '#numFriends')->getText();
        $numSupporters = $page->find('css', '#numSupporters')->getText();
        $numSupportees = $page->find('css', '#numSupportees')->getText();

        //click add friend and wait for page to load
        $page->find('css', '#control-panel-desktop .add-friend')->click();
        $session->wait(60000, '(0 === jQuery.active)');

        //click add supporter and wait for page to load
        $page->find('css', '#control-panel-desktop .add-supporter')->click();
        $session->wait(60000, '(0 === jQuery.active)');

        //click add supportee and wait for page to load
        $page->find('css', '#control-panel-desktop .add-supportee')->click();
        $session->wait(60000, '(0 === jQuery.active)');

        $this->assertNotEquals($numFriends, $page->find('css', '#numFriends')->getText());
        $this->assertNotEquals($numSupporters, $page->find('css', '#numSupporters')->getText());
        $this->assertNotEquals($numSupportees, $page->find('css', '#numSupportees')->getText());

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/profile/natasha-romanov');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .add-wp')->hasAttribute('disabled'));

        $page->find('css', '#control-panel-desktop .add-wp')->click();
        $session->wait(3000);
        $session->wait(60000, '(0 === jQuery.active)');

        $this->assertEquals(true, $page->find('css', '#control-panel-desktop .add-wp')->hasAttribute('disabled'));

        $session->visit('http://127.0.0.1:80/app_test.php/authenticate/logout');

        $username = 'profileNatasha@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/profile/steve-rogers');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals(false, $page->find('css', '#control-panel-desktop .add-patient')->hasAttribute('disabled'));

        $page->find('css', '#control-panel-desktop .add-patient')->click();
        $session->wait(3000);
        $session->wait(60000, '(0 === jQuery.active)');

        $this->assertEquals(true, $page->find('css', '#control-panel-desktop .add-patient')->hasAttribute('disabled'));

    }


    /**
     * Test to ensure all buttons work as expected
     */
    public function testButtonFunctionalitymobile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'profileBucky@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Set the screen size
        $session->resizeWindow(900, 800, 'current');

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/profile/steve-rogers');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $numFriends = $page->find('css', '#numFriends')->getText();
        $numSupporters = $page->find('css', '#numSupporters')->getText();
        $numSupportees = $page->find('css', '#numSupportees')->getText();

        //click add friend and wait for page to load
        $page->find('css', '#control-panel-mobile .add-friend')->click();
        $session->wait(60000, '(0 === jQuery.active)');

        //click add supporter and wait for page to load
        $page->find('css', '#control-panel-mobile .add-supporter')->click();
        $session->wait(60000, '(0 === jQuery.active)');

        //click add supportee and wait for page to load
        $page->find('css', '#control-panel-mobile .add-supportee')->click();
        $session->wait(60000, '(0 === jQuery.active)');

        $this->assertNotEquals($numFriends, $page->find('css', '#numFriends')->getText());
        $this->assertNotEquals($numSupporters, $page->find('css', '#numSupporters')->getText());
        $this->assertNotEquals($numSupportees, $page->find('css', '#numSupportees')->getText());

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/profile/natasha-romanov');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals(false, $page->find('css', '#control-panel-mobile .add-wp')->hasAttribute('disabled'));

        $page->find('css', '#control-panel-mobile .add-wp')->click();
        $session->wait(3000);
        $session->wait(60000, '(0 === jQuery.active)');

        $this->assertEquals(true, $page->find('css', '#control-panel-mobile .add-wp')->hasAttribute('disabled'));

        $session->visit('http://127.0.0.1:80/app_test.php/authenticate/logout');

        $username = 'profileNatasha@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/profile/steve-rogers');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals(false, $page->find('css', '#control-panel-mobile .add-patient')->hasAttribute('disabled'));

        $page->find('css', '#control-panel-mobile .add-patient')->click();
        $session->wait(3000);
        $session->wait(60000, '(0 === jQuery.active)');

        $this->assertEquals(true, $page->find('css', '#control-panel-mobile .add-patient')->hasAttribute('disabled'));

    }

    /**
     * Test to ensure the mobile version is loading and the desktop version is loading
     */
    public function testProfileMobile()
    {

        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Set the screen size
        $session->resizeWindow(900, 800, 'current');

        //Log in
        $un = "profileBucky@gmail.com";
        $pw = "password";

        $session->setBasicAuth($un,$pw);

        //step 3:
        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/profile');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Check to make sure the welcome image is visible
        $this->assertFalse($page->find('css', '#control-panel-desktop')->isVisible());
        $this->assertTrue($page->find('css', '#control-panel-mobile')->isVisible());
        //Wait for page to load (need different trigger)

        //Set the screen size
        $session->resizeWindow(1100, 800, 'current');
        $session->wait(1000 );
        $this->assertTrue($page->find('css', '#control-panel-desktop')->isVisible());
        $this->assertFalse($page->find('css', '#control-panel-mobile')->isVisible());

    }
}