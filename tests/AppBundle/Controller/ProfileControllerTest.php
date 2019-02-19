<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\User;
use Exception;

use AppBundle\DataFixtures\AppFixtures;
use AppBundle\Services\getCountFromDB;
use AppBundle\Services\GetRelationshipFromDB;
use Tests\AppBundle\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use AppBundle\DataFixtures\ProfileFixtures;


/**
 * unit tets for relationship controller to add a client
 *
 * @version 1.0
 * @author cst245
 */
class ProfileControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     * @var mixed
     */
    private $em;

    /**
     *  sets up the DB connection and loads fixtures
     */
    protected function setUp()
    {
        self::bootKernel();

        //Apply the primer
        DatabasePrimer::prime(self::$kernel);

        //Set entity manager
        $this->em = DatabasePrimer::$entityManager;

        $fixture = new ProfileFixtures();
        $fixture->load($this->em);
    }

    /**
     * Tears down DB connection and deletes DB
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
     *  Test to ensure a user receives their own profile data
     */
    public function testGetOwnProfile()
    {
        $url = "http://127.0.0.1/app_test.php/profile/view/bucky-barnes";

        $username = 'profileBucky@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals('Bucky Barnes', $response->body->data->user->name);
        $this->assertEquals(0, $response->body->data->relationships->isFriend);
        $this->assertEquals(0, $response->body->data->relationships->isSupporter);
        $this->assertEquals(0, $response->body->data->relationships->isSupportee);
        $this->assertEquals(0, $response->body->data->relationships->isWellnessProRel);

    }
    /**
     * Test to ensure a not logged in user cannot get profile data
     */
    public function testGetProfileNotLoggedIn()
    {
        $url = "http://127.0.0.1/app_test.php/profile/view/bucky-barnes";

        $response = \Httpful\Request::get($url)->send();

        //ERROR Call to a member function getWellnessProfessional() on string (500 Internal Server Error)
        $this->assertEquals(302, $response->code);

    }

    /**
     *  tests user looking at other user profile
     */
    public function testGetProfileOtherUserNoRel()
    {
        $url = "http://127.0.0.1/app_test.php/profile/view/steve-rogers";

        $username = 'profileBucky@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals('Steve Rogers', $response->body->data->user->name);
        $this->assertEquals(0, $response->body->data->relationships->isFriend);
        $this->assertEquals(0, $response->body->data->relationships->isSupporter);
        $this->assertEquals(0, $response->body->data->relationships->isSupportee);
        $this->assertEquals(0, $response->body->data->relationships->isWellnessProRel);
    }

    /**
     * tests getting friend's profile
     */
    public function testGetProfileOtherUserFriends()
    {
        $url = "http://127.0.0.1/app_test.php/profile/view/bruce-banner";

        $username = 'profileBucky@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals('Bruce Banner', $response->body->data->user->name);
        $this->assertEquals(1, $response->body->data->relationships->isFriend);

    }

    /**
     *  tests getting supporter profile
     */
    public function testGetProfileOtherUserSupporter()
    {
        $url = "http://127.0.0.1/app_test.php/profile/view/tony-stark";

        $username = 'profileBucky@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals('Tony Stark', $response->body->data->user->name);

        $this->assertEquals(1, $response->body->data->relationships->isSupportee);

    }

    /**
     *  test getting WP profile no relation
     */
    public function testGetProfileWPNoRel()
    {
        $url = "http://127.0.0.1/app_test.php/profile/view/natasha-romanov";

        $username = 'profileBucky@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals('Natasha Romanov', $response->body->data->user->name);
        $this->assertNotEquals(0, $response->body->data->user->isWellnessPro);

        $this->assertEquals(0, $response->body->data->relationships->isSupporter);
        $this->assertEquals(0, $response->body->data->relationships->isSupportee);
        $this->assertEquals(0, $response->body->data->relationships->isWellnessProRel);
    }

    /**
     *  test getting WP profile relation exists
     */
    public function testGetProfileWPPatientRel()
    {
        $url = "http://127.0.0.1/app_test.php/profile/view/vision-jones";

        $username = 'profileBucky@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals('Vision Jones', $response->body->data->user->name);
        $this->assertNotEquals(0, $response->body->data->user->isWellnessPro);

        $this->assertEquals(0, $response->body->data->relationships->isSupporter);
        $this->assertEquals(0, $response->body->data->relationships->isSupportee);
        $this->assertEquals(1, $response->body->data->relationships->isWellnessProRel);
    }

    /**
     *  tests WP getting other user profile
     */
    public function testWPGetProfileOtherUserNoRel()
    {
        $url = "http://127.0.0.1/app_test.php/profile/view/bucky-barnes";

        $username = 'profileNatasha@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals('Bucky Barnes', $response->body->data->user->name);
        $this->assertEquals(0, $response->body->data->user->isWellnessPro);

        $this->assertEquals(0, $response->body->data->relationships->isSupporter);
        $this->assertEquals(0, $response->body->data->relationships->isSupportee);
        $this->assertEquals(0, $response->body->data->relationships->isWellnessProRel);
    }

    /**
     *  test WP getting patient profile
     */
    public function testWPGetProfileOtherUserPatientRel()
    {
        $url = "http://127.0.0.1/app_test.php/profile/view/bucky-barnes";

        $username = 'profileVision@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertEquals('success', $response->body->status);
        $this->assertEquals('Bucky Barnes', $response->body->data->user->name);
        $this->assertEquals(0, $response->body->data->user->isWellnessPro);

        $this->assertEquals(0, $response->body->data->relationships->isSupporter);
        $this->assertEquals(0, $response->body->data->relationships->isSupportee);
        $this->assertEquals(1, $response->body->data->relationships->isWellnessProRel);
    }

    /**
     *  test accessing profile of user who doesn't exist
     */
    public function testGetProfileUserNotExist()
    {
        $url = "http://127.0.0.1/app_test.php/profile/view/thor";

        $username = 'profileBucky@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        //AppBundle:User object not found. (404 Not Found)
        $this->assertTrue($response->code === 404);

    }

}