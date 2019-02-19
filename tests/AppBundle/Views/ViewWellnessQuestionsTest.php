<?php
namespace Tests\AppBundle\Views;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink\Driver;
use Behat\Mink\Driver\Selenium2Driver;
use DMore\ChromeDriver\ChromeDriver;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use AppBundle\DataFixtures\ViewWellnessFixtures;
//Step 1:
use Tests\AppBundle\DatabasePrimer;

/**
 * Unit testing for nearby wellness professional front end
 *
 * @version 1.0
 * @author cst231
 */
class ViewWellnessQuestionsTest extends WebTestCase
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

        $this->fixture = new ViewWellnessFixtures();
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
     * As a user I want to be able to view the Wellness Questions
     */
    public function testViewWellnessQuestions()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'wellnessSherlock@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/howareyoufeeling');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //make sure that the user's name is visible on the page
        $this->assertEquals('Welcome Sherlock Holmes! How are you Feeling?', $page->find('css', '#welcome')->getText());

        //make sure that the sliders start at value 0
        $this->assertEquals(0, $page->find('css', '#mood')->getValue());
        $this->assertEquals(0, $page->find('css', '#sleep')->getValue());
        $this->assertEquals(0, $page->find('css', '#thoughts')->getValue());
        $this->assertEquals(0, $page->find('css', '#energy')->getValue());

        //make sure that the only nav link that is visible to the user is for logging out
        $this->assertEquals(true, $page->find('css', '#logout_link')->isVisible());
        $this->assertEquals(false, $page->find('css', '#my-profile_link')->isVisible());
        $this->assertEquals(false, $page->find('css', '#myStats_link')->isVisible());
        $this->assertEquals(false, $page->find('css', '#nearbyWP_link')->isVisible());
        $this->assertEquals(false, $page->find('css', '#viewConversations')->isVisible());

    }

    /**
     * As a user I view the wellness questions and answer with valid values desktop
     */
    public function testAnswerQuestionsValid()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'wellnessSherlock@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/howareyoufeeling');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //set the slider values to 5
        $page->find('css', '#mood-input')->setValue('5');
        $page->find('css', '#sleep-input')->setValue('5');
        $page->find('css', '#thoughts-input')->setValue('5');
        $page->find('css', '#energy-input')->setValue('5');

        //click the button to submit your wellness question answers
        $page->find('css', '#submit')->click();

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //get the page after you have changed the slider values
        $page = $session->getPage();

        $session->wait(1000);

        //makes sure that you are redirected to your profile page
        $this->assertEquals('Sherlock Holmes', $page->find('css', '#fullname')->getText());
        $this->assertEquals('http://127.0.0.1/app_test.php/profile/', $session->getCurrentUrl());


    }

    /**
     * As a user I already answers wellness questions today desktop
     */
    public function testAlreadyAnsweredQuestionsToday()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'wellnessMycroft@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/howareyoufeeling');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();
        $session->wait(3000);

        //checks to see that the sliders aren't on the page
        $this->assertEquals(null, $page->find('css', '#mood-input'));
        $this->assertEquals(null, $page->find('css', '#sleep-input'));
        $this->assertEquals(null, $page->find('css', '#thoughts-input'));
        $this->assertEquals(null, $page->find('css', '#energy-input'));

        //makes sure that you are redirected to your profile page
        $this->assertEquals('Mycroft Holmes', $page->find('css', '#fullname')->getText());
        $this->assertEquals('http://127.0.0.1/app_test.php/profile/', $session->getCurrentUrl());

    }

    /**
     * As a user I want to be able to view the wellness questions on desktop but I’m not logged in
     */
    public function testWellnessQuestionsNotLoggedIn()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/howareyoufeeling');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        $this->assertEquals('http://127.0.0.1/app_test.php/register?error=Invalid%20Credentials', $session->getCurrentUrl());
    }

    /**
     * As a user I want to be able to view the wellness questions on desktop but I’m not a respondent
     */
    public function testWellnessQuestionsNotResp()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'wellnessJohn@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/howareyoufeeling');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //checks to see that the sliders aren't on the page
        $this->assertEquals(null, $page->find('css', '#mood-input'));
        $this->assertEquals(null, $page->find('css', '#sleep-input'));
        $this->assertEquals(null, $page->find('css', '#thoughts-input'));
        $this->assertEquals(null, $page->find('css', '#energy-input'));

        //makes sure that you are redirected to your profile page
        $this->assertEquals('John Watson', $page->find('css', '#fullname')->getText());

        //CHECK URL
        $this->assertEquals('http://127.0.0.1/app_test.php/profile/', $session->getCurrentUrl());
    }

    /**
     * As a user I answer the wellness questions on desktop but leave slider values at zeros
     */
    public function testAnswerQuestionsZero()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'wellnessSherlock@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/howareyoufeeling');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //click submit without changing sliders
        $page->find('css', '#submit')->click();

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        $this->assertEquals(true, $page->find('css', '.jsonError')->isVisible());

        //check text of the json error message
        $this->assertEquals("Your mood is important to us, so please make sure it's between 1 and 10.", $page->find('css', '.jsonError')->getText());
    }

    /**
     * As a user I log in, leave before answering wellness questions, and log back in again
     */
    public function testViewQuestionsLoggedInAndOut()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'wellnessSherlock@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/howareyoufeeling');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Get the page node
        $page = $session->getPage();

        //set the slider values to 5
        $page->find('css', '#mood-input')->setValue('5');
        $page->find('css', '#sleep-input')->setValue('5');
        $page->find('css', '#thoughts-input')->setValue('5');
        $page->find('css', '#energy-input')->setValue('5');

        //click the button to submit your wellness question answers
        $page->find('css', '#submit')->click();

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //Logout
        $session->visit('http://127.0.0.1:80/app_test.php/authenticate/logout');

        //log back in
        $session->setBasicAuth($username, $attemptedPassword);
        $session->visit('http://127.0.0.1/app_test.php/howareyoufeeling');
        $session->wait(60000, '(0 === jQuery.active)');

        $this->assertEquals('Sherlock Holmes', $page->find('css', '#fullname')->getText());

        //CHECK URL
        $this->assertEquals('http://127.0.0.1/app_test.php/profile/', $session->getCurrentUrl());
    }

    /**
     * testing trying to navigate away from wellness questions without answering, resirected back
     */
    public function testRedirectAfterNavAway()
    {
        //Get a local copy of the session
        $session = $this->mink->getSession();

        $username = 'wellnessSherlock@gmail.com';
        $attemptedPassword = 'password';

        $session->setBasicAuth($username, $attemptedPassword);

        //Visit the page
        $session->visit('http://127.0.0.1/app_test.php/howareyoufeeling');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        //navigate to profile
        $session->visit('http://127.0.0.1/app_test.php/profile');

        //Wait for page to load
        $session->wait(60000, '(0 === jQuery.active)');

        $this->assertEquals('http://127.0.0.1/app_test.php/howareyoufeeling/', $session->getCurrentUrl());


    }


}