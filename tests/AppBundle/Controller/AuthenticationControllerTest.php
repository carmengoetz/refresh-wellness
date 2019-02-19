<?php
namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Wellness;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\DataFixtures\RelationshipFixtures;
use AppBundle\Services\HardLogIn;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use AppBundle\Entity\User;
use AppBundle\DataFixtures\AuthFixtures;
use Tests\AppBundle\DatabasePrimer;
/**
 *
 *
 * @version 1.0
 * @author
 */
class AuthenticationControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    private $record;

    /**
     * Setup for testing. connecting to database
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        //Apply the primer
        DatabasePrimer::prime(self::$kernel);

        //Set entity manager
        $this->em = DatabasePrimer::$entityManager;

        $fixture = new AuthFixtures();
        $fixture->load($this->em);
        $fixture->loadWP($this->em);

    }

    /**
     * Summary of testUserLogsIn
     */
    public function testUserLogsIn()
    {

        $url = "http://127.0.0.1/app_test.php/authenticate/login";

        $username = 'authenticationController@userone.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');

    }

    /**
     * Summary of testUserLogsOut
     */
    public function testUserLogsOut()
    {
        $url = "http://127.0.0.1/app_test.php/authenticate/login";

        $username = 'authenticationController@userone.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $url = "http://127.0.0.1/app_test.php/authenticate/logout";

        $response = \Httpful\Request::get($url)->send();

        //rediteredting
        $this->assertEquals($response->code, 302);
    }

    ///**
    // * Summary of testUserLogsOutNotSignedIn
    // */
    //public function testUserLogsOutNotSignedIn()
    //{
    //    $url = "http://127.0.0.1/authenticate/logout";

    //    $response = \Httpful\Request::get($url)->send();

    //    $this->assertTrue($response->body->status === 'failure');
    //    $this->assertTrue($response->body->message == 'Already logged-out.');
    //}

    /**
     * Summary of testUserLogsInNotExist
     */
    public function testUserLogsInNotExist()
    {

        $url = "http://127.0.0.1/app_test.php/authenticate/login";

        $username = 'ihatedogs@gmail.com';
        $attemptedPassword = '123';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        //HTTP code for unauthorized is 401
        $this->assertEquals($response->code, 302);

    }

    /**
     * Summary of testUserLogsInIncorrectPassword
     */
    public function testUserLogsInIncorrectPassword()
    {

        $url = "http://127.0.0.1/app_test.php/authenticate/login";

        $username = 'authenticationController@userone.com';
        $attemptedPassword = '123';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        //HTTP code for unauthorized is 401
        $this->assertEquals($response->code, 302);

    }

    /**
     * Summary of testViewsOwnStatsLoggedIn
     */
    public function testViewsOwnStatsLoggedIn()
    {
        $username = 'authenticationController@usertwo.com';
        $attemptedPassword = 'password';

        $url = "http://127.0.0.1/app_test.php/stats/me";
        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');

    }

    /**
     * Summary of testViewsOwnStatsLoggedOut
     */
    public function testViewsOwnStatsLoggedOut()
    {
        $url = "http://127.0.0.1/app_test.php/stats/me";
        $response = \Httpful\Request::get($url)->send();

        $this->assertTrue($response->code == 302);

    }

    /**
     * Summary of testViewsCaregiveeStatsLoggedIn
     */
    public function testViewsCaregiveeStatsLoggedIn()
    {

        $url = "http://127.0.0.1/app_test.php/stats/caregiver/1FAC2763-9FC0-FC21-4762-42330CEBACC6";

        $username = 'authenticationController@userone.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');
        $this->assertTrue($response->body->data->id === '1FAC2763-9FC0-FC21-4762-42330CEBACC6');
        $this->assertTrue(count($response->body->data->stats) > 0); //got back atleast 1 record
        $this->assertTrue(strlen($response->body->message) == 0);

    }

    /**
     * Summary of testViewsCaregiveeStatsLoggedOut
     */
    public function testViewsCaregiveeStatsLoggedOut()
    {
        $url = "http://127.0.0.1/app_test.php/stats/caregiver/1FAC2763-9FC0-FC21-4762-42330CEBAC69";
        $response = \Httpful\Request::get($url)->send();

        $this->assertTrue($response->code == 302);
    }

    /**
     * Summary of testViewNearbyWPLoggedIn
     */
    public function testViewNearbyWPLoggedIn()
    {
        $url = "http://127.0.0.1/app_test.php/WellnessProfessionals/view/1";

        $guid = '1FAC2763-9FC0-FC21-4762-42330CEBACC7';

        $username = 'authenticationController@userone.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();


        //Assert that the response was successful
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
     * Summary of testViewNearbyWPLoggedOut
     */
    public function testViewNearbyWPLoggedOut()
    {

        $url = "http://127.0.0.1/app_test.php/WellnessProfessionals/view/1";


        $response = \Httpful\Request::get($url)->send();


        //Assert that the response was successful
        $this->assertTrue($response->code == 302);

    }

    /**
     * Summary of testAddFriendLoggedIn
     */
    public function testAddFriendLoggedIn()
    {

        $userLogin = $this->em
              ->getRepository(User::class)
              ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEBACC7']);

        $username = 'authenticationController@userone.com';
        $attemptedPassword = 'password';

        $url = "http://127.0.0.1/app_test.php/relationship/addFriend/1FAC2763-9FC0-FC21-4762-42330CEBACC6";
        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');

        $this->em->refresh($userLogin);

        $this->assertTrue($userLogin->getNumFriends() == 1);

        $userFriend = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEBACC6']);


        //REPLACES A QUERY FOR THE RELATIONSHIP
        $rel = null;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
        	if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEBACC6' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEBACC7' )
            {
                $rel = $tempRel;
            }
        }

        $this->assertTrue(isset($rel));

        $this->assertTrue($rel->getType() === 'friend');

        $this->em->refresh($userFriend);

        $this->assertTrue($userFriend->getNumFriends() == 1);

        $userFriend->setNumFriends(0);
        $this->em->persist($userFriend);

        $userLogin->setNumFriends(0);
        $this->em->persist($userLogin);

        $this->em->remove($rel);
        $this->em->flush();

    }

    /**
     * Summary of testAddFriendLoggedOut
     */
    public function testAddFriendLoggedOut()
    {
        $url = "http://127.0.0.1/app_test.php/relationship/addFriend/1FAC2763-9FC0-FC21-4762-42330CEBACC6";
        $response = \Httpful\Request::get($url)->send();

        //unauthorized since not logged in
        $this->assertEquals($response->code, 302);

    }



    /**
     * Summary of testViewWellnessQuestionsLoggedOut
     */
    public function testViewWellnessQuestionsLoggedOut()
    {
        $wellnessJSON = array(
            "mood" => 5,
            "energy" => 5,
            "sleep" => 5,
            "thoughts" => 5
        );

        $url = "localhost/app_test.php/howareyoufeeling/submitanswers/" . urlencode(json_encode($wellnessJSON));
        $response = \Httpful\Request::post($url)->send();

        //make sure the JSON response has a status of "success"
        $this->assertEquals($response->body->status, "failure");
    }

    /**
     * deletes the created wellness object and tears down the database connection
     */
    protected function tearDown()
    {
        $fixture = new AuthFixtures();
        $fixture->unloadWP($this->em);
        $fixture->unload($this->em);


        //if (!empty($this->record))
        //{
        //    $this->em->remove($this->record);
        //    $this->em->flush();
        //}
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}
