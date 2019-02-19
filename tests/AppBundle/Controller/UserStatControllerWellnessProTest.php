<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\DataFixtures\CaregiverStatFixtures;
use AppBundle\Entity\Wellness;
use AppBundle\Entity\User;
use AppBundle\Entity\Respondent;
use AppBundle\Entity\Relationship;
use AppBundle\DataFixtures\RelationshipFixtures;
use AppBundle\DataFixtures\OrgMemberStatFixtures;
use AppBundle\DataFixtures\UserStatWPFixtures;
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
class UserStatControllerWellnessProTest extends WebTestCase
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

        $fixture2 = new UserStatWPFixtures();
        $fixture2->load($this->em);


    }

    /**
     *
     * Tests to ensure that a WP can view their patients' stats individually
     */
    public function testWPRequestsPatientStatsSuccess()
    {

        $url = "http://127.0.0.1/app_test.php/stats/patient/2FAC2763-9FC0-FC21-4762-42330CEUSWP6";

        $username = 'userstatwpcontroller@userone.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');
        $this->assertTrue(count($response->body->data->stats) > 0); //got back atleast 1 record
        $this->assertTrue(strlen($response->body->message) == 0);
    }

    /**
     *
     * Tests to ensure that a WP cannot view patients' stats who are not theirs
     *
     *
     */
    public function testWPRequestsPatientStatsNotTheirPatient()
    {
        $url = "http://127.0.0.1/app_test.php/stats/patient/2FAC2763-9FC0-FC21-4762-42330CEUSWP5";

        $username = 'userstatwpcontroller@userone.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue(count($response->body->data->stats) == 0); //got back no records
        $this->assertTrue($response->body->message === 'You do not have the correct permissions to view this users stats.');
    }

    /**
     *
     * Tests to ensure that a WP cannot view patients' stats who do not exist
     */
    public function testWPRequestsPatientStatsUserDoesNotExist()
    {

        $url = "http://127.0.0.1/app_test.php/stats/patient/2FAC2763-9FC0-FC21-4762-42330CEUSWP9";
        $username = 'userstatwpcontroller@userone.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue(count($response->body->data->stats) == 0); //got back no records
        $this->assertTrue($response->body->message === 'The user specified does not exist.');
    }

    /**
     *
     * Tests to ensure that a WP cannot view patients' stats when they aren't logged in
     *
     * Temporarily removed until log in is available
     */
    public function testRequestPatientStatsNotLoggedIn()
    {
        $url = "http://127.0.0.1/app_test.php/stats/patient/2FAC2763-9FC0-FC21-4762-42330CEUSWP7";
        $response = \Httpful\Request::get($url)->send();

        $this->assertTrue($response->code == 302);
    }

    /**
     *
     * Tests to ensure that a OA can view their OMs' stats individually
     */
    public function testOARequestsOMStatsSuccess()
    {

        $url = "http://127.0.0.1/app_test.php/stats/orgMember/3AABE519-CEB2-4962-BCE2-397BA83USWP1/3FAC2763-9FC0-FC21-4762-42330CEUSWP6";
        $username = 'userstatwpcontroller@userfour.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');
        $this->assertTrue(count($response->body->data->stats) > 0); //got back atleast 1 record
        $this->assertTrue(strlen($response->body->message) == 0);
    }

    /**
     *
     * Tests to ensure that a OA cannot view OMs' stats who are not theirs
     *
     *
     */
    public function testOARequestsOMStatsNotInTheirOrg()
    {
        $url = "http://127.0.0.1/app_test.php/stats/orgMember/3AABE519-CEB2-4962-BCE2-397BA83USWP1/3FAC2763-9FC0-FC21-4762-42330CEUSWP5";

        $username = 'userstatwpcontroller@userfour.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue(count($response->body->data->stats) == 0); //got back no records
        $this->assertTrue($response->body->message === 'The specified user is not a member of this organization.');
    }

    /**
     *
     * Tests to ensure that a OA cannot view OMs' stats who do not exist
     */
    public function testOARequestsOMStatsUserDoesNotExist()
    {

        $url = "http://127.0.0.1/app_test.php/stats/orgMember/3AABE519-CEB2-4962-BCE2-397BA83USWP1/3FAC2763-9FC0-FC21-4762-42330CEUSWP9";
        $username = 'userstatwpcontroller@userfour.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue(count($response->body->data->stats) == 0); //got back no records
        $this->assertTrue($response->body->message === 'The user specified does not exist.');
    }

    /**
     *
     * Tests to ensure that a OA cannot view OMs' stats when they aren't logged in
     *
     * Temporarily removed until log in is available
     */
    public function testRequestOMStatsNotLoggedIn()
    {
        $url = "http://127.0.0.1/app_test.php/stats/orgMember/3AABE519-CEB2-4962-BCE2-397BA83USWP1/3FAC2763-9FC0-FC21-4762-42330CEUSWP7";
        $response = \Httpful\Request::get($url)->send();

        $this->assertTrue($response->code == 302);
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