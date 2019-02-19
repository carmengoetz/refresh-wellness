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
class ViewRegisterAsWPTest extends WebTestCase
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


        //Set the drivers for the page for testing
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
     * Test to ensure that a WP can register
     */
    public function testRegisterWP()
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

        //Click the register tab
        $page->pressButton("registerBtn");

        //Assert that the WP checkbox is visible, the other fields aren't and the checkbox is unchecked
        $this->assertEquals(true, $page->find("css", "#user_isWellPro")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_practiceName")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_contactNumber")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_contactEmail")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_website")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_isWellPro")->isChecked());


        //Click the checkbox
        $page->find("css", "#user_isWellPro")->click();

        //Assert that all the wellness fields are visible
        $this->assertEquals(true, $page->find("css", "#user_isWellPro")->isChecked());
        $this->assertEquals(true, $page->find("css", "#user_wellnessPro_practiceName")->isVisible());
        $this->assertEquals(true, $page->find("css", "#user_wellnessPro_contactNumber")->isVisible());
        $this->assertEquals(true, $page->find("css", "#user_wellnessPro_contactEmail")->isVisible());
        $this->assertEquals(true, $page->find("css", "#user_wellnessPro_website")->isVisible());

        $page->find("css", "#user_email")->setValue("registerWP@gmail.com");
        $page->find("css", "#user_plainPassword_first")->setValue('Pa$$w0rd');
        $page->find("css", "#user_plainPassword_second")->setValue('Pa$$w0rd');
        $page->find("css", "#user_firstName")->setValue("Wellness");
        $page->find("css", "#user_lastName")->setValue("Professional");
        $page->find("css", "#user_birthDate")->setValue("1969-04-20");
        $page->find("css", "#user_city")->setValue("Saskatoon");
        $page->find("css", "#user_country")->setValue("CA");
        $page->find("css", "#user_termsAccepted")->click();

        $page->find("css", "#user_wellnessPro_practiceName")->setValue("Wellness Practice");
        $page->find("css", "#user_wellnessPro_contactNumber")->setValue("306 123 4567");
        $page->find("css", "#user_wellnessPro_contactEmail")->setValue("contactWP@gmail.com");
        $page->find("css", "#user_wellnessPro_website")->setValue("http://www.wp.com");

        //Click register
        $page->pressButton("register");

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Assert we have registered successfully
        $this->assertEquals("User created, you may now log in!", $page->find("css", "h1")->getText());

        //Go back and log in
        $session->visit('http://127.0.0.1:80/app_test.php/register');

        //Wait for page to load (need different trigger)

        $session->wait(60000, '(0 === jQuery.active)');

        //Click the login tab
        $page->pressButton("loginBtn");

        $page->find("css", "#_username")->setValue("registerWP@gmail.com");
        $page->find("css", "#_password")->setValue('Pa$$w0rd');

        $page->pressButton("login");

        //Wait for page to load
        $session->wait(3000);

        $this->assertEquals('Wellness Professional', $page->find('css', '#fullname')->getText());
        $this->assertEquals(true, $page->find("css", "#pracName")->isVisible());

    }

    /**
     * Test to ensure that a respondent can register
     */
    public function testRegisterRespondent()
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

        //Click the register tab
        $page->pressButton("registerBtn");

        //Assert that the WP checkbox is visible, the other fields aren't and the checkbox is unchecked
        $this->assertEquals(true, $page->find("css", "#user_isWellPro")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_practiceName")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_contactNumber")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_contactEmail")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_website")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_isWellPro")->isChecked());

        $page->find("css", "#user_email")->setValue("registerResp@gmail.com");
        $page->find("css", "#user_plainPassword_first")->setValue('Pa$$w0rd');
        $page->find("css", "#user_plainPassword_second")->setValue('Pa$$w0rd');
        $page->find("css", "#user_firstName")->setValue("Resp");
        $page->find("css", "#user_lastName")->setValue("Ondent");
        $page->find("css", "#user_birthDate")->setValue("1969-04-20");
        $page->find("css", "#user_city")->setValue("Saskatoon");
        $page->find("css", "#user_country")->setValue("CA");
        $page->find("css", "#user_termsAccepted")->click();

        //Click register
        $page->pressButton("register");

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Assert we have registered successfully
        $this->assertEquals("User created, you may now log in!", $page->find("css", "h1")->getText());

        //Go back and log in
        $session->visit('http://127.0.0.1:80/app_test.php/register');

        //Wait for page to load (need different trigger)

        $session->wait(60000, '(0 === jQuery.active)');

        //Click the login tab
        $page->pressButton("loginBtn");


        $page->find("css", "#_username")->setValue("registerResp@gmail.com");
        $page->find("css", "#_password")->setValue('Pa$$w0rd');

        $page->pressButton("login");

        //Wait for page to load
        $session->wait(3000);

        $this->assertEquals('Welcome Resp Ondent! How are you Feeling?', $page->find('css', '#welcome')->getText());
        

    }


    /**
     * Test to ensure that a WP can register without filling in optional fields
     */
    public function testRegisterWPNoOptionals()
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

        //Click the register tab
        $page->pressButton("registerBtn");

        //Assert that the WP checkbox is visible, the other fields aren't and the checkbox is unchecked
        $this->assertEquals(true, $page->find("css", "#user_isWellPro")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_practiceName")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_contactNumber")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_contactEmail")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_website")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_isWellPro")->isChecked());


        //Click the checkbox
        $page->find("css", "#user_isWellPro")->click();

        //Assert that all the wellness fields are visible
        $this->assertEquals(true, $page->find("css", "#user_isWellPro")->isChecked());
        $this->assertEquals(true, $page->find("css", "#user_wellnessPro_practiceName")->isVisible());
        $this->assertEquals(true, $page->find("css", "#user_wellnessPro_contactNumber")->isVisible());
        $this->assertEquals(true, $page->find("css", "#user_wellnessPro_contactEmail")->isVisible());
        $this->assertEquals(true, $page->find("css", "#user_wellnessPro_website")->isVisible());

        $page->find("css", "#user_email")->setValue("registerWP@gmail.com");
        $page->find("css", "#user_plainPassword_first")->setValue('Pa$$w0rd');
        $page->find("css", "#user_plainPassword_second")->setValue('Pa$$w0rd');
        $page->find("css", "#user_firstName")->setValue("Wellness");
        $page->find("css", "#user_lastName")->setValue("Professional");
        $page->find("css", "#user_birthDate")->setValue("1969-04-20");
        $page->find("css", "#user_city")->setValue("Saskatoon");
        $page->find("css", "#user_country")->setValue("CA");
        $page->find("css", "#user_termsAccepted")->click();

        $page->find("css", "#user_wellnessPro_practiceName")->setValue("Wellness Practice");


        //Click register
        $page->pressButton("register");

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Assert we have registered successfully
        $this->assertEquals("User created, you may now log in!", $page->find("css", "h1")->getText());

        //Go back and log in
        $session->visit('http://127.0.0.1:80/app_test.php/register');

        //Wait for page to load (need different trigger)

        $session->wait(60000, '(0 === jQuery.active)');

        //Click the login tab
        $page->pressButton("loginBtn");

        $page->find("css", "#_username")->setValue("registerWP@gmail.com");
        $page->find("css", "#_password")->setValue('Pa$$w0rd');

        $page->pressButton("login");

        //Wait for page to load
        $session->wait(3000);

        $this->assertEquals('Wellness Professional', $page->find('css', '#fullname')->getText());
        $this->assertEquals(true, $page->find("css", "#pracName")->isVisible());

    }

    /**
     * Test to ensure that a WP can not register without filling in required field
     */
    public function testRegisterWPNoReqFields()
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

        //Click the register tab
        $page->pressButton("registerBtn");

        //Assert that the WP checkbox is visible, the other fields aren't and the checkbox is unchecked
        $this->assertEquals(true, $page->find("css", "#user_isWellPro")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_practiceName")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_contactNumber")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_contactEmail")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_wellnessPro_website")->isVisible());
        $this->assertEquals(false, $page->find("css", "#user_isWellPro")->isChecked());


        //Click the checkbox
        $page->find("css", "#user_isWellPro")->click();

        //Assert that all the wellness fields are visible
        $this->assertEquals(true, $page->find("css", "#user_isWellPro")->isChecked());
        $this->assertEquals(true, $page->find("css", "#user_wellnessPro_practiceName")->isVisible());
        $this->assertEquals(true, $page->find("css", "#user_wellnessPro_contactNumber")->isVisible());
        $this->assertEquals(true, $page->find("css", "#user_wellnessPro_contactEmail")->isVisible());
        $this->assertEquals(true, $page->find("css", "#user_wellnessPro_website")->isVisible());

        $page->find("css", "#user_email")->setValue("registerWP@gmail.com");
        $page->find("css", "#user_plainPassword_first")->setValue('Pa$$w0rd');
        $page->find("css", "#user_plainPassword_second")->setValue('Pa$$w0rd');
        $page->find("css", "#user_firstName")->setValue("Wellness");
        $page->find("css", "#user_lastName")->setValue("Professional");
        $page->find("css", "#user_birthDate")->setValue("1969-04-20");
        $page->find("css", "#user_city")->setValue("Saskatoon");
        $page->find("css", "#user_country")->setValue("CA");
        $page->find("css", "#user_termsAccepted")->click();

        //Click register
        $page->pressButton("register");

        //Wait for page to load (need different trigger)
        $session->wait(60000, '(0 === jQuery.active)');

        //Assert we haven't left the page
        $this->assertEquals("http://127.0.0.1/app_test.php/register", $session->getCurrentUrl());

    }

}