<?php
namespace Tests\AppBundle\Views;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink\Driver;
use Behat\Mink\Driver\Selenium2Driver;
use DMore\ChromeDriver\ChromeDriver;
use AppBundle\DataFixtures\RelationshipFixtures;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Tests\AppBundle\DatabasePrimer;

/**
 * Unit testing for nearby wellness professional front end
 *
 * @version 1.0
 * @author cst231
 */
class ViewLoginTest extends WebTestCase
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

        //Load fixtures. Just using RelationshipFixtures because we only need to borrow 1 user.
        $this->fixture = new RelationshipFixtures();
        $this->fixture->load($this->em);

        //Set the drivers for the page for testing
        $this->driver = new ChromeDriver('http://localhost:9222', null, 'http://127.0.0.1:80');
        $this->mink = new Mink(array('browser' => new Session(new ChromeDriver('http://127.0.0.1:9222', null, 'http://127.0.0.1:80'))));

        $this->mink->setDefaultSessionName('browser');
    }


    /**
     * Test to ensure that the home page looks good on mobile
     */
    public function testViewLoginMobile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Set the screen size
        $session->resizeWindow(900, 800, 'current');

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
     * Test to ensure that the home page looks good on desktop
     */
    public function testViewLoginDesktop()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Set the screen size
        $session->resizeWindow(1100, 800, 'current');

        //step 3:
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
     * Test to ensure that you can use the home page to login successfully
     */
    public function testLoginSuccessfuly()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "cst.project5.refresh+test1@gmail.com";
        $pw = "password";

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/register');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Fill out the form elements and press login button
        $page->fillField('_username',$un);
        $page->fillField('_password',$pw);
        $page->pressButton('login');

        //Wait a specific amount of time for page to load
        $session->wait(3000);

        //Check URL - Change For Wellness Questions - ADD LATER

        //Verify we are on the profile page
        $this->assertEquals('http://127.0.0.1/app_test.php/profile/', $session->getCurrentUrl());
        //Verify that the fullname h1 exists
        $this->assertTrue($page->find("css", "#fullname")->isVisible());
        //Assert that the content of the h1 is the name of the logged in user
        $this->assertEquals("User One", $page->find("css", "#fullname")->getText());
    }

    /**
     * Test to ensure that a user cannot login with an email that does not exist
     */
    public function testLoginUnsuccessfullyNotInDB()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "coolguy@reallycool.com";
        $pw = "NobodyknowsHowCoolMyPasswordIs";

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/register');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Fill out the form elements and press login button
        $page->fillField('_username',$un);
        $page->fillField('_password',$pw);
        $page->pressButton('login');

        //Wait a specific amount of time for page to load
        $session->wait(3000);

        //Verify we are still on the login page
        $this->assertEquals('http://127.0.0.1/app_test.php/register?error=Invalid%20Credentials', $session->getCurrentUrl());
        //Verify that the fullname h1 does not exist
        $this->assertEquals(null, $page->find("css", "#fullname"));
        //Assert that the correct span element contains are expected error message
        $this->assertEquals($page->find("css", ".form-error-message")->getText(), "Invalid Credentials");
    }

    /**
     * Test to ensure that a user cannot login with an email that is in an incorrect format
     */
    public function testLoginUnsuccessfullyEmailNotRightFormat()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "TheCoolestGuy";
        $pw = "NobodyknowsHowCoolMyPasswordIs";

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/register');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Fill out the form elements and press login button
        $page->fillField('_username',$un);
        $page->fillField('_password',$pw);
        $page->pressButton('login');

        //Wait a specific amount of time for page to load
        $session->wait(3000);

        //Verify we are still on the login page
        $this->assertEquals('http://127.0.0.1/app_test.php/register#', $session->getCurrentUrl());
        //Verify that the fullname h1 does not exist
        $this->assertEquals(null, $page->find("css", "#fullname"));
        //Assert that the correct span element contains are expected error message
        $this->assertEquals($page->find("css", ".form-error-message")->getText(), "This value is not a valid email address.");
    }

    /**
     * Test to ensure that a user cannot login with an password that does not exist for the selected user
     */
    public function testLoginUnsuccessfullyIncorrectPassword()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Log in
        $un = "cst.project5.refresh+test1@gmail.com";
        $pw = "IncorrectPasswordForAllensAccount";

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/register');

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Fill out the form elements and press login button
        $page->fillField('_username',$un);
        $page->fillField('_password',$pw);
        $page->pressButton('login');

        //Wait a specific amount of time for page to load
        $session->wait(3000);

        //Verify we are still on the login page
        $this->assertEquals('http://127.0.0.1/app_test.php/register?error=Invalid%20Credentials', $session->getCurrentUrl());
        //Verify that the fullname h1 does not exist
        $this->assertEquals(null, $page->find("css", "#fullname"));
        //Assert that the correct span element contains are expected error message
        $this->assertEquals($page->find("css", ".form-error-message")->getText(), "Invalid Credentials");
    }




    /**
     * Test to ensure that the navbar has a logout button if the user is logged in and redirect when clicked
     */
    public function testUserGoesDirectlyToPageWithNavbarAndLogsOut()
    {
        $session = $this->mink->getSession();

        $username = "cst.project5.refresh+test1@gmail.com";
        $attemptedPassword = "password";


        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');


        //Wait for page to load (need different trigger)
        $session->wait(3000);

        //Get the page node
        $page = $session->getPage();

        //Visible/Not visible
        $this->assertTrue($page->find('css', '#logout_link') != null);
        $this->assertTrue($page->find('css', '#login_link') == null);

        //click on the logout link
        $page->find('css', '#logout_link')->click();

        //Wait a specific amount of time for page to load
        $session->wait(3000);

        //Verify we get to the register/login page
        $this->assertEquals('http://127.0.0.1/app_test.php/register', $session->getCurrentUrl());

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