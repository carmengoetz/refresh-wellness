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
use AppBundle\DataFixtures\UserStatFixtures;
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
class UserStatControllerTest extends WebTestCase
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

        $fixture = new UserStatFixtures();
        $fixture->load($this->em);


    }

    /**
     * Test to ensure that a valid user can access thier own stats
     */
    public function testUserRequestsStats()
    {

        $url = "http://127.0.0.1/app_test.php/stats/me";

        $username = 'userstatcontroller@usertwo.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');
        $this->assertTrue(count($response->body->data->stats) > 0); //got back atleast 1 record
        $this->assertTrue(strlen($response->body->message) == 0);
    }

        /**
     * Test to ensure that a valid user can access thier own stats, but they have no stats
     */
    public function testUserRequestsStatsNotRespondent()
    {
        $repo = $this->em->getRepository(Respondent::class);
        $respToFind = $repo->findOneBy(['user'=>'1FAC2763-9FC0-FC21-4762-42330CEB9US6']);

        $this->em->remove($respToFind);

        $this->em->flush();

        $url = "http://127.0.0.1/app_test.php/stats/me";

        $username = 'userstatcontroller@usertwo.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue(count($response->body->data->stats) == 0); //got back no records
        $this->assertTrue(strlen($response->body->message) > 0);

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