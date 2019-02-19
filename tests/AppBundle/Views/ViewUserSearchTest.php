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
use AppBundle\DataFixtures\UserSearchFixtures;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Tests\AppBundle\DatabasePrimer;

/**
 * Unit tests for front end of viewing messages
 *
 * @version 1.0
 * @author cst236
 */

class ViewUserSearchTest extends WebTestCase
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
        $fixture = new UserSearchFixtures();
        $fixture->load($this->em);

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
     * Test to ensure that a user can search and recieves one result
     */
    public function testUserSearchOneResult()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'HomerSimpson@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/profile');

        $page = $session->getPage();

        //Search for Marge Simpson
        $page->find('css', '#search-bar')->setValue('Marge Simpson');
        $page->find('css', '#search-button')->click(); //Change to use enter key over click
        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        $page = $session->getPage();
        //Ensure that the page was redirected
        $this->assertEquals($session->getCurrentUrl(), 'http://127.0.0.1/app_test.php/search/', "Page was not redirected");

        //Find the name and city of the expected result
        $name = $page->find('css', '.search-results-name')->getText();
        $city = $page->find('css', '.search-results-city')->getText();

        //Ensure that the returned values match what was expected
        $this->assertEquals($name ,"Marge Simpson","Error in returned name.");
        $this->assertEquals($city ,"Springfield","Error in returned city.");

        //Check to see if the correct number of results were returned
        $totalRslt = $page->findAll('css', '.search-result');
        $this->assertEquals(count($totalRslt), 1, "Error in number of returned results.");

        //Ensure that the error message is not displayed
        $this->assertFalse($page->find('css', '#jsonError')->isVisible(), "Error message was displayed");
    }

    /**
     * Test to ensure that multiple results are returned by the search
     */
    public function testUserSearchMultipleResult()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'HomerSimpson@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/profile');

        $page = $session->getPage();

        $page->find('css', '#search-bar')->setValue('simpson');
        $page->find('css', '#search-button')->click();
        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        //Check to see if the correct number of results were returned
        $totalRslt = $page->findAll('css', '.search-result');
        $this->assertEquals(count($totalRslt), 6, "Error in number of returned results.");
    }


    /**
     * Test to ensure that a search that has no results is handled properly
     */
    public function testUserSearchNoResults()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'HomerSimpson@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/profile');

        $page = $session->getPage();

        $page->find('css', '#search-bar')->setValue('diet');
        $page->find('css', '#search-button')->click();
        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        //Ensure that the error message is displayed on the page
        $this->assertTrue($page->find('css', '#jsonError')->isVisible(), "Error message was not displayed");
        $error = $page->find('css', '#jsonError')->getText();

        //Ensure that the error message contains the correct text
        $this->assertEquals($error, "Your search yielded no results.", "Error in the displayed error message");

        //Check to see if the correct number of results were returned
        $totalRslt = $page->findAll('css', '.search-result');
        $this->assertEquals(count($totalRslt), 0, "Error in number of returned results.");
    }

    /**
     * Test to ensure a search for an empty string does not return restults
     */
    public function testUserSearchEmpty()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'HomerSimpson@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/profile');

        $page = $session->getPage();

        $page->find('css', '#search-button')->click();

        //Ensure that the page was not redirected
        $this->assertEquals($session->getCurrentUrl(), 'http://127.0.0.1/app_test.php/profile/', "Page was redirected");

    }

    /**
     * Test to ensure that all 50 results are returned
     */
    public function testUserSearchMany()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'HomerSimpson@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/profile');

        $page = $session->getPage();

        $page->find('css', '#search-bar')->setValue('bort');
        $page->find('css', '#search-button')->click();
        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        //Check to see if the correct number of results were returned
        $totalRslt = $page->findAll('css', '.search-result');
        $this->assertEquals(count($totalRslt), 20, "Error in number of returned results on the 1st page");

        $page->find('css', '#next')->click();
        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        //Check to see if the correct number of results were returned
        $totalRslt = $page->findAll('css', '.search-result');
        $this->assertEquals(count($totalRslt), 20, "Error in number of returned results on the 2nd page");

        $page->find('css', '#next')->click();
        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        //Check to see if the correct number of results were returned
        $totalRslt = $page->findAll('css', '.search-result');
        $this->assertEquals(count($totalRslt), 10, "Error in number of returned results on the 3rd page");

    }

    /**
     * Test to ensure that when a user clicks on a user result
     * they are redirected to a user's profile
     */
    public function testUserSearchGoToProfile()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'HomerSimpson@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/profile');

        $page = $session->getPage();

        //Search for Marge Simpson
        $page->find('css', '#search-bar')->setValue('Marge Simpson');
        $page->find('css', '#search-button')->click();
        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        //Find the name and city of the expected result
        $page->find('css', '.search-results-name')->click();

        $this->assertEquals($session->getCurrentUrl(), 'http://127.0.0.1/app_test.php/profile/margeSimpsonID', "Page was not redirected");

    }
}