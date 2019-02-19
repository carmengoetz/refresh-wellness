<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\DataFixtures\WPAggStatFixtures;
use AppBundle\Entity\Wellness;
use AppBundle\Entity\User;
use AppBundle\Entity\Respondent;
use AppBundle\Entity\Relationship;
use Tests\AppBundle\DatabasePrimer;

/**
 * Tests to ensure that the UserStatController handles requests properly
 * When accessing the /stats/patientAll route (WP views patients' stats in aggregate)
 *
 * @version 1.0
 * @author cst231
 */
class WPAggStatControllerTest extends WebTestCase
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

        $fixture = new WPAggStatFixtures();
        $fixture->load($this->em);
    }

    /**
     * Test that a logged in WP can get all of their many patients' stats
     */
    public function testWPRequestsPatientStatsAggSuccessMany()
    {
        $url = "http://127.0.0.1/app_test.php/stats/patientAll";

        $username = 'cst.project5.refresh+test41@gmail.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');
        $this->assertEquals(3, count($response->body->data->patients)); //got all 3 of their patients
        $this->assertEquals(0, strlen($response->body->message)); //no message
    }

    /**
     * Test that a logged in WP can get their patients' stats, but they don't have any patients
     */
    public function testWPRequestsPatientStatsAggSuccessNone()
    {
        /* WHEN LOGIN STORY IS COMPLETE, SIMPLY LOGIN WP #2 (HAS NO PATIENTS) INSTEAD OF REMOVING WP #2's PATIENTS */
        //Remove hard-coded logged-in wellness pros' relationships with respondents
        //Remove relationship between user1 and user3
        //$relationship = $this->em->getRepository(Relationship::class)
        //    ->findOneBy(array('relationshipId' => '4FAC2763-9FC0-FC21-4762-42330CEB9BC3:4FAC2763-9FC0-FC21-4762-42330CEB9BC1:wellnessprofessional'));
        //if(!empty($relationship))
        //{
        //    $this->em->remove($relationship);
        //}

        ////Remove relationship between user1 and user4
        //$relationship = $this->em->getRepository(Relationship::class)
        //    ->findOneBy(array('relationshipId' => '4FAC2763-9FC0-FC21-4762-42330CEB9BC4:4FAC2763-9FC0-FC21-4762-42330CEB9BC1:wellnessprofessional'));
        //if(!empty($relationship))
        //{
        //    $this->em->remove($relationship);
        //}

        ////Remove relationship between user1 and user5
        //$relationship = $this->em->getRepository(Relationship::class)
        //    ->findOneBy(array('relationshipId' => '4FAC2763-9FC0-FC21-4762-42330CEB9BC5:4FAC2763-9FC0-FC21-4762-42330CEB9BC1:wellnessprofessional'));
        //if(!empty($relationship))
        //{
        //    $this->em->remove($relationship);
        //}

        ////Flush the DB
        //$this->em->flush();

        $url = "http://127.0.0.1/app_test.php/stats/patientAll";
        $username = 'cst.project5.refresh+test42@gmail.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');
        $this->assertEquals(0, count($response->body->data->patients)); //got no patients
        $this->assertEquals(0, strlen($response->body->message)); //no message
    }

    /**
     * Test that a user that is not logged in cannot get any patients' stats
     * WILL NOT WORK UNTIL LOGIN STORY IS COMPLETE
     */
    public function testWPRequestsPatientStatsAggNotLoggedIn()
    {
        $url = "http://127.0.0.1/app_test.php/stats/patientAll";
        $response = \Httpful\Request::get($url)->send();

        $this->assertTrue($response->code == 302);
    }

    /**
     * Teardown instructions for each test
     */
    protected function tearDown()
    {
        //Unload the fixture
        $fixture = new WPAggStatFixtures();
        $fixture->unload($this->em);

        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}