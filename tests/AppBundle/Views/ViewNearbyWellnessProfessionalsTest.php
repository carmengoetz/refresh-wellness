<?php
namespace Tests\AppBundle\Views;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\DataFixtures\RelationshipFixtures;
use Symfony\Component\HttpKernel\Client;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink\Driver;
use Behat\Mink\Driver\Selenium2Driver;
use DMore\ChromeDriver\ChromeDriver;
use AppBundle\DataFixtures\ViewWPFixtures;
use Tests\AppBundle\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Unit testing for nearby wellness professional front end
 *
 * @version 1.0
 * @author cst231
 */
class ViewNearbyWellnessProfessionalsTest extends WebTestCase
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
        //$_SERVER['KERNEL_DIR'] = './app';
        self::bootKernel();

        $application = new Application(self::$kernel);
        $application->setAutoExit(false);

        $options = array('command' => 'doctrine:database:drop', '--force' => true);
        $application->run(new ArrayInput($options));

        //Apply the primer
        DatabasePrimer::prime(self::$kernel);



        //Set entity manager
        $this->em = DatabasePrimer::$entityManager;




        $fixture = new ViewWPFixtures();
        $fixture->load($this->em);
        $fixture->loadWP($this->em);

        $this->driver = new ChromeDriver('http://localhost:9222', null, 'http://127.0.0.1:80');
        $this->mink = new Mink(array('browser' => new Session(new ChromeDriver('http://127.0.0.1:9222', null, 'http://127.0.0.1:80'))));

        $this->mink->setDefaultSessionName('browser');
    }

    /**
     * Test to ensure that the pages load correctly when there are more than 10 wellness professionals.
     */
    public function testViewWellnessProfessionalsMany()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //$session->setRequestHeader('test', '1FAC2763-9FC0-FC21-4762-42330CEB9BC8');

        $username = 'viewWellnessPro@userthree.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');
        $session->wait(1000);
        //Get the page node
        $page = $session->getPage();

        //Ensure that we get the first 10 wellness professionals in Winnipeg
        $this->assertTrue(count($page->findAll("css", ".wellnessPro")) == 10);

        //Find and Click 'nextPage' link
        $page->find("css", "#next")->click();

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Ensure that the page that loads as a result has the remaining 2 wellness professionals
        $this->assertTrue(count($page->findAll("css", ".wellnessPro")) == 3);
    }


    /**
     * Test to ensure that the page loads the three wellness professionals correctly.
     */
    public function testViewWellnessProfessionalsSuccess()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'viewWellnessPro@userone.com';
        $attemptedPassword = 'password';

        //$session->setRequestHeader('test', '1FAC2763-9FC0-FC21-4762-42330CEB9BC7');
        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Ensure that we get the three wellness professionals in saskatoon
        $this->assertEquals(3, count($page->findAll("css", ".wellnessPro")));

    }

    /**
     * Test to ensure that the page loads correctly when there are no wellness professionals.
     */
    public function testViewWellnessProfessionalsNone()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //$session->setRequestHeader('test', '1FAC2763-9FC0-FC21-4762-42330CEB9BC6');
        $username = 'viewWellnessPro@usertwo.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1:80/app_test.php/WellnessProfessionals/nearby');


        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //Ensure that we get zero wellness professionals in Calgary
        $this->assertTrue(count($page->findAll("css", "div.wellnessProfessional")) == 0);

        $this->assertTrue($page->find('css', '#jsonError')->getText() === 'No wellness professionals found in Calgary');

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