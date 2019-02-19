<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\DataFixtures\OrgAggStatFixtures;
use AppBundle\Entity\Wellness;
use AppBundle\Entity\User;
use AppBundle\Entity\Respondent;
use AppBundle\Entity\GroupMember;
use Tests\AppBundle\DatabasePrimer;
/**
 * Tests to ensure that the UserStatController handles requests properly
 * When accessing the /stats/orgMemberAll route (OA views members' stats in aggregate)
 *
 * @version 1.0
 * @author cst231, cst245
 */
class OrgAggStatControllerTest extends WebTestCase
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

        $fixture = new OrgAggStatFixtures();
        $fixture->load($this->em);
    }

    /**
     * Test that a logged in Org Admin (OA) can get their org members' stats when they have the max amount of org members (assuming it is 5 for now)
     */
    public function testOARequestsMemberStatsAggSuccessMax()
    {
        $url = "http://127.0.0.1/app_test.php/stats/orgMemberAll";

        $username = 'orgAdminAggregate@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertTrue($response->body->status === 'success');
        $this->assertEquals(5, count($response->body->data->members)); //got all 5 of their members
        $this->assertEquals(0, strlen($response->body->message)); //no message
    }

    /**
     * Test that a logged in Org Admin (OA) can get their org members' stats
     */
    public function testOARequestsMemberStatsAggSuccessThree()
    {
        //Remove two members of the group

        //removing member 1
        $orgMember = $this->em->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'ORG2816A-314C-4647-88F9-ECD5CA4F47F1:FC22816A-314C-4647-88F9-ECD5CA4F47F2'));

        if (!empty($orgMember))
        {
            $this->em->remove($orgMember);
        }

        //removing member 2
        $orgMember = $this->em->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'ORG2816A-314C-4647-88F9-ECD5CA4F47F1:FC22816A-314C-4647-88F9-ECD5CA4F47F3'));

        if (!empty($orgMember))
        {
            $this->em->remove($orgMember);
        }

        //Flush the DB
        $this->em->flush();

        //Send request
        $url = "http://127.0.0.1/app_test.php/stats/orgMemberAll";

        $username = 'orgAdminAggregate@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertTrue($response->body->status === 'success');
        $this->assertEquals(3, count($response->body->data->members)); //got all 3 of their members
        $this->assertEquals(0, strlen($response->body->message)); //no message
    }

    /**
     * Test that a logged in Org Admin (OA) can get their org members' stats, but they don't have any members
     */
    public function testOARequestsMemberStatsAggSuccessNone()
    {
        //Remove all members of the group

        //removing member 1
        $orgMember = $this->em->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'ORG2816A-314C-4647-88F9-ECD5CA4F47F1:FC22816A-314C-4647-88F9-ECD5CA4F47F2'));

        if (!empty($orgMember))
        {
            $this->em->remove($orgMember);
        }

        //removing member 2
        $orgMember = $this->em->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'ORG2816A-314C-4647-88F9-ECD5CA4F47F1:FC22816A-314C-4647-88F9-ECD5CA4F47F3'));

        if (!empty($orgMember))
        {
            $this->em->remove($orgMember);
        }

        //removing member 3
        $orgMember = $this->em->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'ORG2816A-314C-4647-88F9-ECD5CA4F47F1:FC22816A-314C-4647-88F9-ECD5CA4F47F4'));

        if (!empty($orgMember))
        {
            $this->em->remove($orgMember);
        }

        //removing member 4
        $orgMember = $this->em->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'ORG2816A-314C-4647-88F9-ECD5CA4F47F1:FC22816A-314C-4647-88F9-ECD5CA4F47F5'));

        if (!empty($orgMember))
        {
            $this->em->remove($orgMember);
        }

        //removing member 5
        $orgMember = $this->em->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'ORG2816A-314C-4647-88F9-ECD5CA4F47F1:FC22816A-314C-4647-88F9-ECD5CA4F47F6'));

        if (!empty($orgMember))
        {
            $this->em->remove($orgMember);
        }

        //Flush the DB
        $this->em->flush();

        //Send request
        $url = "http://127.0.0.1/app_test.php/stats/orgMemberAll";

        $username = 'orgAdminAggregate@gmail.com';
        $password = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $password)->send();

        $this->assertTrue($response->body->status === 'success');
        $this->assertEquals(0, count($response->body->data->members)); //got no members
        $this->assertEquals(0, strlen($response->body->message)); //no message
    }

    /**
     * Test that a user that is not logged in cannot get any members' stats
     */
    public function testOARequestsMemberStatsAggNotLoggedIn()
    {
        $url = "http://127.0.0.1/app_test.php/stats/orgMemberAll";
        $response = \Httpful\Request::get($url)->send();

        $this->assertTrue($response->code == 302);
    }

    /**
     * Teardown instructions for each test
     */
    protected function tearDown()
    {
        //Unload the fixture
        $fixture = new OrgAggStatFixtures();
        $fixture->unload($this->em);

        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}