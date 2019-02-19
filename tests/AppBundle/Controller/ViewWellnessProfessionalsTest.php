<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\TestCase;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Exception;
use AppBundle\DataFixtures\RelationshipFixtures;
use AppBundle\Services\HardLogIn;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\WellnessProfessionalsController;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use AppBundle\DataFixtures\ViewWPFixtures;
use Tests\AppBundle\DatabasePrimer;



/**
 * unit tests for viewing nearby wellness pros controller front end
 *
 * @version 1.0
 * @author cst233
 */
class ViewWellnessProfessionalsTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     * @var mixed
     */
    private $em;
    //user to be created from database
    private $userLogin;
    private $client = null;

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

        $fixture = new ViewWPFixtures();
        $fixture->load($this->em);
        $fixture->loadWP($this->em);

    }

    /**
     * test to make sure a user can view nearby wellness professionals
     */
    public function testViewWellnessProfessionals()
    {
        //Make sure a user is logged in
        //$this->login('1FAC2763-9FC0-FC21-4762-42330CEB9BC7');

        //Navigate to the URL
        $url = "http://127.0.0.1/app_test.php/WellnessProfessionals/view/1";

        $username = 'viewWellnessPro@userone.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');

        //Assert that the number of data items found is equal to 3
        $this->assertTrue($response->body->data->totalFound == 3);

        //Assert that the page number is 1
        $this->assertTrue($response->body->data->pageNumber == 1);

        //Assert that the number of actual objects returned is 3
        $this->assertTrue(count($response->body->data->objects) === 3);

        //Loop through all objects and ensure that their parameters are correct and existing

        foreach ($response->body->data->objects as $object)
        {
            //Assert that the type of the objects is "wellness professional"
            $this->assertTrue($object->type === "WellnessProfessional");

            //Assert that the city of the objects is "Saskatoon"
            $this->assertTrue($object->objectData->city === "Saskatoon");

            //Assert that all the other parameters exist
            $this->assertTrue(property_exists($object->objectData, 'practiceName'));
            $this->assertTrue(property_exists($object->objectData, 'contactNumber'));
            $this->assertTrue(property_exists($object->objectData, 'contactEmail'));
            $this->assertTrue(property_exists($object->objectData, 'website'));

        }


    }


    /**
     * test to make sure a user cannot view nearby wellness professionals if they are not logged in
     */
    public function testViewWellnessProfessionalsNotLoggedIn()
    {

        //Navigate to the URL
        $url = "http://127.0.0.1/app_test.php/WellnessProfessionals/view/1";

        $response = \Httpful\Request::get($url)->send();


        //Assert that the response was successful
        $this->assertTrue($response->code == 302);

    }

    /**
     * test to make sure a user recieves an appropriate error message when no wellness professionals are found in their city
     */
    public function testViewWellnessProfessionalsNoneFound()
    {
        //Make sure a user is logged in
        //$this->login('1FAC2763-9FC0-FC21-4762-42330CEB9BC6');

        //Navigate to the URL
        $url = "http://127.0.0.1/app_test.php/WellnessProfessionals/view/1";

        $username = 'viewWellnessPro@usertwo.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        //Assert that the response was successful
        $this->assertTrue($response->body->status === 'failure');

        //Assert that the error message is correct
        $this->assertTrue($response->body->message === 'No wellness professionals found in Calgary');

        //Assert that there is no data found
        $this->assertTrue(empty($response->body->data));


    }

    /**
     * test to make sure that a user can only view the first ten WPs found when there are more than 10 in their community
     */
    public function testViewWellnessProfessionalsMoreThan10Page1()
    {
        //Make sure a user is logged in
        //$this->login('1FAC2763-9FC0-FC21-4762-42330CEB9BC9');

        //Navigate to the URL
        $url = "http://127.0.0.1/app_test.php/WellnessProfessionals/view/1";

        $username = 'viewWellnessPro@userfour.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        //Assert that the response was successful
        $this->assertTrue($response->body->status === 'success');

        //Assert that the number of data items found is equal to 3
        $this->assertTrue($response->body->data->totalFound == 13);

        //Assert that the page number is 1
        $this->assertTrue($response->body->data->pageNumber == 1);

        //Assert that the number of actual objects returned is 3
        $this->assertTrue(count($response->body->data->objects) === 10);


    }

    /**
     * test to make sure that a user can only view the next 2 WPs found when there are more than 10 in their community
     */
    public function testViewWellnessProfessionalsMoreThan10Page2()
    {
        //Make sure a user is logged in
        //$this->login('1FAC2763-9FC0-FC21-4762-42330CEB9BC9');

        //Navigate to the URL
        $url = "http://127.0.0.1/app_test.php/WellnessProfessionals/view/2";

        $username = 'viewWellnessPro@userfour.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        //Assert that the response was successful
        $this->assertTrue($response->body->status === 'success');

        //Assert that the number of data items found is equal to 3
        $this->assertTrue($response->body->data->totalFound == 13);

        //Assert that the page number is 1
        $this->assertTrue($response->body->data->pageNumber == 2);

        //Assert that the number of actual objects returned is 3
        $this->assertTrue(count($response->body->data->objects) === 3);


    }

    /**
     * test to make sure that a user can only view valid page numbers
     */
    public function testViewWellnessProfessionalsPage0()
    {
        //Make sure a user is logged in
        //$this->login('1FAC2763-9FC0-FC21-4762-42330CEB9BC9');

        //Navigate to the URL
        $url = "http://127.0.0.1/app_test.php/WellnessProfessionals/view/0";

        $username = 'viewWellnessPro@userfour.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        //Assert that the response was successful
        $this->assertTrue($response->body->status === 'failure');

        //Assert that the error message is correct
        $this->assertTrue($response->body->message === 'Invalid page request');

        //Assert that there is no data found
        $this->assertTrue(empty($response->body->data));


    }

    /**
     * test to make sure that a user can only view valid page numbers
     */
    public function testViewWellnessProfessionalsPage3()
    {
        //Make sure a user is logged in
        //$this->login('1FAC2763-9FC0-FC21-4762-42330CEB9BC9');

        //Navigate to the URL
        $url = "http://127.0.0.1/app_test.php/WellnessProfessionals/view/3";

        $username = 'viewWellnessPro@userfour.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        //Assert that the response was successful
        $this->assertTrue($response->body->status === 'failure');

        //Assert that the error message is correct
        $this->assertTrue($response->body->message === 'Invalid page request');

        //Assert that there is no data found
        $this->assertTrue(empty($response->body->data));


    }


    /**
     * deletes the created user and tears down the database connection
     */
    protected function tearDown()
    {

        $fixture = new ViewWPFixtures();
        $fixture->unloadWP($this->em);
        $fixture->unload($this->em);


        if (!empty($this->userLogin))
        {
            $this->em->remove($this->userLogin);
            $this->em->flush();
        }
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}