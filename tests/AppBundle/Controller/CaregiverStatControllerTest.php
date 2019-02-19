<?php
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\DataFixtures\CaregiverStatFixtures;
use AppBundle\Entity\Wellness;
use AppBundle\DataFixtures\RelationshipFixtures;
use AppBundle\DataFixtures\CGStatFixtures;
use Tests\AppBundle\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
/**
 * Tests to ensure that the caregiver stat controller handles requests properly by returning
 * the expected JSON responses.
 *
 * @version 1.0
 * @author cst231, cst238
 */
class CaregiverStatControllerTest extends WebTestCase
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

        $fixture = new CGStatFixtures();
        $fixture->load($this->em);

    }

    /**
     * Test to ensure that a valid caregiver can access a user's stats who is actually their caregivee
     */
    public function testCaregiverRequestsStats()
    {

        $url = "http://127.0.0.1/app_test.php/stats/caregiver/1FAC2763-9FC0-FC21-4762-42330CEB9CG6";

        $username = 'cst.project5.refresh+CG1@gmail.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');
        $this->assertTrue($response->body->data->id === '1FAC2763-9FC0-FC21-4762-42330CEB9CG6');
        $this->assertTrue(count($response->body->data->stats) > 0); //got back atleast 1 record
        $this->assertTrue(strlen($response->body->message) == 0);

    }

    /**
     * Tests that the proper error message and status of failure is returned when attempting to view stats
     * for a user that does not exist.
     */
    public function testCaregiverRequestsStatsNotExist()
    {

        $url = "http://127.0.0.1/app_test.php/stats/caregiver/1FAC2763-9FC0-FC21-4762-42330CEB9B69";
        $username = 'cst.project5.refresh+CG1@gmail.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        //unauthorized
        $this->assertTrue($response->body->status === "failure");
        $this->assertTrue($response->body->message == "The user specified does not exist.");
    }

    /**
     * Tests that the proper error message and status of failure is returned when attempting to view stats
     * for a user that is not the caregiver's caregivee.
     */
    public function testCaregiverRequestsStatsNotCaregivee()
    {

        $url = "http://127.0.0.1/app_test.php/stats/caregiver/2FAC2763-9FC0-FC21-4762-42330CEB9CG5";
        $username = 'cst.project5.refresh+CG1@gmail.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->data->id === '2FAC2763-9FC0-FC21-4762-42330CEB9CG5');
        $this->assertTrue(count($response->body->data->stats) == 0);
        $this->assertTrue($response->body->message === 'You do not have the correct permissions to view this users stats.');

    }

    /**
     * Tests that an empty array is returned for stats when looking at a valid caregivee's stats who does not
     * have any records in the wellness table.
     */
    public function testCaregiverRequestsStatsNoStats()
    {


        //Get the response
        $url = "http://127.0.0.1/app_test.php/stats/caregiver/new-user-two";
        $username = 'userOneWellnessStuff@gmail.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');
        $this->assertTrue($response->body->data->id == 'new-user-two');
        $this->assertEquals(0,sizeof($response->body->data->stats));
        $this->assertTrue(strlen($response->body->message) == 0);

    }

    /**
     * Teardown instructions for each test
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