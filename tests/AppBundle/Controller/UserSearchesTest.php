<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\User;
use AppBundle\DataFixtures\UserSearchFixtures;
use Tests\AppBundle\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Tests to ensure that the search functionality can return users
 * and groups and properly handles errors
 *
 * @version 1.0
 * @author cst236
 */
class UserSearchesTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     * @var mixed
     */
    private $em;

    /**
     * Set up for the fixture to boot the kernel
     */
    protected function setUp()
    {
        self::bootKernel();

        //Apply the primer
        DatabasePrimer::prime(self::$kernel);

        //Set entity manager
        $this->em = DatabasePrimer::$entityManager;

        $fixture = new UserSearchFixtures();
        $fixture->load($this->em);


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
        //Set up the login for homer simpson
        $username = 'HomerSimpson@gmail.com';
        $password = 'password';

        //create a search for marge
        $url = "http://127.0.0.1/app_test.php/search/1/marge%20simpson";

        //login and search
        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        //Check that the search was successful
        $this->assertEquals($response->body->status, "success", "Error obtaining response from page");

        //Ensure that only one result
        //$this->assertEquals($response->body->data->totalFound, 1, "Incorrect number of users returned from database");

        //check that the response has returned the expected results from the database
        $this->assertEquals($response->body->data->objects[0]->type, "respondent", "error in returned object type");
        $this->assertEquals($response->body->data->objects[0]->objectData->id, "margeSimpsonID", "error in returned ID");
        $this->assertEquals($response->body->data->objects[0]->objectData->name, "Marge Simpson", "error in returned name");
        $this->assertEquals($response->body->data->objects[0]->objectData->city, "Springfield", "error in returned city name");
    }

    /**
     * Test to ensure that multiple groups are returned by the search
     */
    public function testUserSearchMultipleResult()
    {
        //Set up the login for homer simpson
        $username = 'HomerSimpson@gmail.com';
        $password = 'password';

        //create a search for groups containing the word doughnuts
        $url = "http://127.0.0.1/app_test.php/search/1/simpson";

        //login and search
        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        //Check that the search was successful
        $this->assertEquals($response->body->status, "success", "Error obtaining response from page");

        //Ensure that two results are found
        //$this->assertEquals($response->body->data->totalFound, 2, "Incorrect number of groups returned from database");

        //check that the response has returned the expected results from the database for both groups
        $this->assertEquals($response->body->data->objects[0]->objectData->id, 'bartSimpsonID', "error in returned ID of group 1");
        $this->assertEquals($response->body->data->objects[0]->objectData->name, "Bart Simpson", "error in returned name  of group 1");
    }

    /**
     * Test to ensure that a search that has no results is handled properly
     */
    public function testUserSearchNoResults()
    {
        //Set up the login for homer simpson
        $username = 'HomerSimpson@gmail.com';
        $password = 'password';

        //create a search for the word diet
        $url = "http://127.0.0.1/app_test.php/search/1/flanders";

        //login and search
        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        //Check that the search was successful
        $this->assertEquals($response->body->status, "failure", "Error obtaining response from page");

        //Check that the page notified the user that the search had no results.
        $this->assertEquals($response->body->message, "Your search yielded no results.", "Incorrect error message returned.");
    }

    /**
     * Test to ensure that all 50 results are returned
     */
    public function testUserSearchMany()
    {
        //Set up the login for homer simpson
        $username = 'HomerSimpson@gmail.com';
        $password = 'password';

        //create a search for nothing
        $url = "http://127.0.0.1/app_test.php/search/1/bort";

        //login and search
        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        //Check that the search was successful
        $this->assertEquals($response->body->status, "success", "Error obtaining response from page");

        //Ensure that 50 users were found
        //$this->assertEquals($response->body->data->totalFound, 50, "Incorrect number of users returned from database");

        //Ensure we are the correct page of results
        //$this->assertEquals($response->body->data->pageNumber, 1, "Incorrect number of users returned from database");

        //Check to make sure only 20 results were returned
        $this->assertEquals(count($response->body->data->objects), 20, "Incorrect number of results returned");

        //Grab the next page of results
        $url = "http://127.0.0.1/app_test.php/search/2/bort"; //not sure if this is how it would be sent. Searches will be trimmed resulting in a space being empty
        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        //Check to make sure only 20 results were returned
        $this->assertEquals(count($response->body->data->objects), 20, "Incorrect number of results returned");

        //Grab the next page of results
        $url = "http://127.0.0.1/app_test.php/search/3/bort"; //not sure if this is how it would be sent. Searches will be trimmed resulting in a space being empty
        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        //Check to make sure only 10 results were returned
        $this->assertEquals(count($response->body->data->objects), 10, "Incorrect number of results returned");

        }
    }