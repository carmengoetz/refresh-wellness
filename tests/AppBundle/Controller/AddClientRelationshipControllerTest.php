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

/**
 * unit tets for relationship controller to add a client
 *
 * @version 1.0
 * @author cst245
 */
class AddClientRelationshipControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     * @var mixed
     */
    private $em;
    private $MAX_CLIENTS = 10;
    private $ONE_UNDER_MAX_CLIENTS = 9;
    private $MAX_WELLNESS_PROFESSIONALS = 10;
    private $CLIENT = 'client';
    private $WELLNESS_PROFESSIONAL = 'wellness professional';
    private $FRIEND = 'friend';

    protected function setUp()
    {
        self::bootKernel();

        //Apply the primer
        DatabasePrimer::prime(self::$kernel);

        //Set entity manager
        $this->em = DatabasePrimer::$entityManager;

        $fixture = new AppFixtures();
        $fixture->loadWP($this->em);
        $fixture->loadClients($this->em);
    }

    /*
     * Testing that a Wellness Professional is able to add a client
     */
    public function testAddClient()
    {
        $professionalLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9B01']);

        $url = "http://127.0.0.1/app_test.php/relationship/addClient/1FAC2763-9FC0-FC21-4762-42330CEB9P01";
        $username = $professionalLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');

        $this->em->refresh($professionalLogin);

        $this->assertEquals(getCountFromDB::countRelationship($professionalLogin->getUserID(), $this->CLIENT, $this->em), 1);

        $userClient = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9P01']);

        $rel = null;

        $relationshipsInitiated = $professionalLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $professionalLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdOne()->getUserID() === '1FAC2763-9FC0-FC21-4762-42330CEB9B01' && $tempRel->getUserIdTwo()->getUserID() === '1FAC2763-9FC0-FC21-4762-42330CEB9P01' )
            {
                $rel = $tempRel;
            }
        }

        $this->assertTrue(isset($rel));
        $this->assertTrue($rel->getType() === $this->CLIENT);

        $this->em->refresh($userClient);

        $this->assertEquals(getCountFromDB::countRelationshipAddedTo($userClient->getUserID(), $this->CLIENT, $this->em, 'pending'), 1);

        $this->em->persist($professionalLogin);
        $this->em->persist($userClient);

        $this->em->remove($rel);
        $this->em->flush();
    }

    /*
     * Testing that a Wellness Professional is able to add up to
     * the max number of clients
     */
    public function testAddMaxClients()
    {
        $professionalLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9B01']);

        for ($i = 1; $i <= $this->MAX_CLIENTS; $i++)
        {
            if ($i <= 9)
            {
                $url = "http://127.0.0.1/app_test.php/relationship/addClient/1FAC2763-9FC0-FC21-4762-42330CEB9P0" . $i;
                $username = $professionalLogin->getUsername();
                $attemptedPassword = 'password';

                $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

                $this->assertTrue($response->body->status === 'success');
            }
            else if ($i == 10)  //adding the 10th client
            {
                $url = "http://127.0.0.1/app_test.php/relationship/addClient/1FAC2763-9FC0-FC21-4762-42330CEB9P" . $i;
                $username = $professionalLogin->getUsername();
                $attemptedPassword = 'password';

                $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

                $this->assertTrue($response->body->status === 'success');
            }
        }


        $this->em->refresh($professionalLogin);

        $this->assertEquals(getCountFromDB::countRelationship($professionalLogin->getUserID(), $this->CLIENT, $this->em), $this->MAX_CLIENTS);

        $userClient = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9P01']);

        $rel = null;

        $relationshipsInitiated = $professionalLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $professionalLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdOne()->getUserID() === '1FAC2763-9FC0-FC21-4762-42330CEB9B01' && $tempRel->getUserIdTwo()->getUserID() === '1FAC2763-9FC0-FC21-4762-42330CEB9P10' )
            {
                $rel = $tempRel;
            }
        }

        $this->assertTrue(isset($rel));
        $this->assertTrue($rel->getType() === $this->CLIENT);

        $this->em->refresh($userClient);

        $this->assertEquals(getCountFromDB::countRelationshipAddedTo($userClient->getUserID(), $this->CLIENT, $this->em, 'pending'), 1);

        $this->em->persist($professionalLogin);
        $this->em->persist($userClient);

        foreach ($relationships as $tempRel)
        {
            $this->em->remove($tempRel);
        }

        $this->em->flush();
    }

    /*
     * Testing that a Wellness Professional is unable to add
     * over the maximum number of clients
     */
    public function testAddOverMax()
    {
        $professionalLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9B01']);

        for ($i = 1; $i <= $this->MAX_CLIENTS; $i++)
        {
            if ($i <= 9)
            {
                $url = "http://127.0.0.1/app_test.php/relationship/addClient/1FAC2763-9FC0-FC21-4762-42330CEB9P0" . $i;
                $username = $professionalLogin->getUsername();
                $attemptedPassword = 'password';

                $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

                $this->assertTrue($response->body->status === 'success');
            }
            else if ($i == 10)
            {
                $url = "http://127.0.0.1/app_test.php/relationship/addClient/1FAC2763-9FC0-FC21-4762-42330CEB9P" . $i;
                $username = $professionalLogin->getUsername();
                $attemptedPassword = 'password';

                $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

                $this->assertTrue($response->body->status === 'success');
            }
        }

        $url = "http://127.0.0.1/app_test.php/relationship/addClient/1FAC2763-9FC0-FC21-4762-42330CEB9P11";
        $username = $professionalLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');

        $this->em->refresh($professionalLogin);

        $this->assertEquals(getCountFromDB::countRelationship($professionalLogin->getUserID(), $this->CLIENT, $this->em), $this->MAX_CLIENTS);

        $userClient = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9P01']);

        $rel = 0;

        $relationshipsInitiated = $professionalLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $professionalLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdOne()->getUserID() === '1FAC2763-9FC0-FC21-4762-42330CEB9B01' && $tempRel->getUserIdTwo()->getUserID() === '1FAC2763-9FC0-FC21-4762-42330CEB9P11' )
            {
                $rel++;
            }
        }

        $this->assertEquals($rel, 0);


        $this->em->refresh($userClient);

        $this->assertEquals(getCountFromDB::countRelationshipAddedTo($userClient->getUserID(), $this->CLIENT, $this->em, 'pending'), 1);

        $this->em->persist($professionalLogin);
        $this->em->persist($userClient);

        foreach ($relationships as $tempRel)
        {
            $this->em->remove($tempRel);
        }
        $this->em->flush();
    }

    /*
     * Testing that a Wellness Professional is unable to add a client
     * that is already in their client list
     */
    public function testClientInList()
    {
        $professionalLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9B01']);

        $url = "http://127.0.0.1/app_test.php/relationship/addClient/1FAC2763-9FC0-FC21-4762-42330CEB9P01";
        $username = $professionalLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');

        $url = "http://127.0.0.1/app_test.php/relationship/addClient/1FAC2763-9FC0-FC21-4762-42330CEB9P01";
        $username = $professionalLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->reason == 'Patient relationship already exists');

        $this->em->refresh($professionalLogin);

        $this->assertEquals(getCountFromDB::countRelationship($professionalLogin->getUserID(), $this->CLIENT, $this->em), 1);

        $userClient = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9P01']);

        $rel = 0;

        $relationshipsInitiated = $professionalLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $professionalLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdOne()->getUserID() === '1FAC2763-9FC0-FC21-4762-42330CEB9B01' && $tempRel->getUserIdTwo()->getUserID() === '1FAC2763-9FC0-FC21-4762-42330CEB9P01' )
            {
                $rel++;
            }
        }

        $this->assertEquals($rel, 1);

        $this->em->refresh($userClient);

        $this->assertEquals(getCountFromDB::countRelationshipAddedTo($userClient->getUserID(), $this->CLIENT, $this->em, 'pending'), 1);

        $this->em->persist($professionalLogin);
        $this->em->persist($userClient);

        foreach ($relationships as $tempRel)
        {
        	$this->em->remove($tempRel);
        }

        $this->em->flush();
    }

    /*
     * Testing that a Wellness Professional is unable to add a client
     * when the user does not exist
     */
    public function testAddClientNotExist()
    {
        $professionalLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9B01']);

        $url = "http://127.0.0.1/app_test.php/relationship/addClient/1FAC2763-9FC0-FC21-4762-12330CEB9P01";
        $username = $professionalLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->reason === 'User not found');

        $this->em->refresh($professionalLogin);

        $this->assertEquals(getCountFromDB::countRelationship($professionalLogin->getUserID(), $this->CLIENT, $this->em), 0);

        $rel = 0;

        $relationshipsInitiated = $professionalLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $professionalLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdOne()->getUserID() === '1FAC2763-9FC0-FC21-4762-42330CEB9B01' && $tempRel->getUserIdTwo()->getUserID() === '1FAC2763-9FC0-FC21-4762-12330CEB9P01' )
            {
                $rel++;
            }
        }

        $this->assertEquals($rel, 0);

    }

    /*
     * Testing that a Wellness Professional is unable to add themselves
     * as a client
     */
    public function testAddSelfAsClient()
    {
        $professionalLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9B01']);

        //using your own guid
        $url = "http://127.0.0.1/app_test.php/relationship/addClient/1FAC2763-9FC0-FC21-4762-42330CEB9B01";
        $username = $professionalLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->reason === 'You cannot add yourself as a client.');

        $this->em->refresh($professionalLogin);

        $this->assertEquals(getCountFromDB::countRelationship($professionalLogin->getUserID(), $this->CLIENT, $this->em), 0);

        $rel = 0;

        $relationshipsInitiated = $professionalLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $professionalLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9B01' && $tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9B01' )
            {
                $rel++;
            }
        }

        $this->assertequals($rel, 0);
    }

    /*
     * Testing that a Wellness Professional is unable to add a client
     * for a user that has had their account disabled
     */
    public function testAddInactiveClient()
    {
        $professionalLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9B01']);

        $url = "http://127.0.0.1/app_test.php/relationship/addClient/1FAC2763-9FC0-FC21-4762-42330CEB9P11";
        $username = $professionalLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->reason === 'target user is disabled');

        $this->em->refresh($professionalLogin);

        $this->assertEquals(getCountFromDB::countRelationship($professionalLogin->getUserID(), $this->CLIENT, $this->em), 0);

        $rel = 0;

        $relationshipsInitiated = $professionalLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $professionalLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdOne()->getUserID() === '1FAC2763-9FC0-FC21-4762-42330CEB9B01' && $tempRel->getUserIdTwo()->getUserID() === '1FAC2763-9FC0-FC21-4762-42330CEB9P11' )
            {
                $rel++;
            }
        }

        $this->assertTrue($rel == 0);
    }

    /*
     * Testing that a Wellness Professional is unable to add a client
     * if the user has a full Wellness Professionals list
     */
    public function testAddClientFullProfessionals()
    {
        $professionalLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9B01']);

        $userFull = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);

        $profNum = 2;

        for ($i = 0; $i < $this->MAX_WELLNESS_PROFESSIONALS; $i++)
        {
        	$url = "http://127.0.0.1/app_test.php/relationship/addWellnessProfessional/1FAC2763-9FC0-FC21-4762-42330CEB9B" . ($profNum < 10 ? '0' : '') . $profNum;
            $username = $userFull->getUsername();
            $attemptedPassword = 'password';

            $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();
            $profNum++;
        }

        $count = getCountFromDB::countRelationship($userFull->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em, 'pending');

        $this->assertEquals($count, 10);

        $url = "http://127.0.0.1/app_test.php/relationship/addClient/1FAC2763-9FC0-FC21-4762-42330CEB9BC7";
        $username = $professionalLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        //$this->assertTrue($response->body->reason === 'Clients wellness professional list is full');

        $this->em->refresh($professionalLogin);

        $this->assertEquals( getCountFromDB::countRelationship($professionalLogin->getUserID(), $this->CLIENT, $this->em), 0);

        $rel = 0;

        $relationshipsInitiated = $professionalLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $professionalLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9B01' && $tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' )
            {
                $rel++;
            }
        }

        $this->assertEquals($rel, 0);

        $this->em->refresh($userFull);

        $this->em->persist($userFull);

        $relationshipsInitiated = $userFull->relationshipsInitiated->getValues();
        $relationshipsRequested = $userFull->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
        	$this->em->remove($tempRel);
        }

        $this->em->flush();
    }

    /*
     * Testing that a Wellness Professional is able to add a client
     * that is also their friend
     */
    public function testAddFriendAsClient()
    {
        $professionalLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9B01']);

        $url = "http://127.0.0.1/app_test.php/relationship/addFriend/1FAC2763-9FC0-FC21-4762-42330CEB9P01";
        $username = $professionalLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();
        $this->assertTrue($response->body->status === 'success');

        $url = "http://127.0.0.1/app_test.php/relationship/addClient/1FAC2763-9FC0-FC21-4762-42330CEB9P01";
        $username = $professionalLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');

        $this->em->refresh($professionalLogin);

        $this->assertEquals(getCountFromDB::countRelationship($professionalLogin->getUserID(), $this->CLIENT, $this->em), 1);
        $this->assertEquals(getCountFromDB::countRelationship($professionalLogin->getUserID(), $this->FRIEND, $this->em), 1);

        $userClient = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9P01']);

        $rel = null;

        $relationshipsInitiated = $professionalLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $professionalLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdOne()->getUserID() === '1FAC2763-9FC0-FC21-4762-42330CEB9B01' && $tempRel->getUserIdTwo()->getUserID() === '1FAC2763-9FC0-FC21-4762-42330CEB9P01' && $tempRel->getType() != 'friend' )
            {
                $rel = $tempRel;
            }
        }

        $this->assertTrue(isset($rel));
        $this->assertTrue($rel->getType() === $this->CLIENT);

        $this->em->refresh($userClient);

        $this->assertEquals(getCountFromDB::countRelationshipAddedTo($userClient->getUserID(), $this->CLIENT, $this->em, 'pending'), 1);

        $this->em->persist($professionalLogin);
        $this->em->persist($userClient);

        foreach ($relationships as $tempRel)
        {
            $this->em->remove($tempRel);
        }

        $this->em->flush();
    }

    /*
     * Testing that a regular user is unable to add another
     * user as a client
     */
    public function testAddClientNotProfessional()
    {
        $userLogin = $this->em
              ->getRepository(User::class)
              ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);

        //this is set up using a call that will use a hard login for the normal user.
        $url = "http://127.0.0.1/app_test.php/relationship/addClientNotWP/1FAC2763-9FC0-FC21-4762-42330CEB9P01";
        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->reason === 'You have not been verified as a qualified wellness professional.');

        $this->em->refresh($userLogin);

        $this->assertEquals(getCountFromDB::countRelationship($userLogin->getUserID(), $this->CLIENT, $this->em), 0);

        $rel = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdOne()->getUserID() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdTwo()->getUserID() === '1FAC2763-9FC0-FC21-4762-42330CEB9P01' )
            {
                $rel++;
            }
        }

        $this->assertEquals($rel, 0);

    }

    /*
     * Tests that the service to list the userID's of the other user in the relationship
     * are properly retrieved and displayed
     */
    public function testGetRelationships()
    {
        $professionalLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9B01']);

        for ($i = 1; $i <= 5; $i++)
        {
            if ($i <= 5)
            {
                $url = "http://127.0.0.1/app_test.php/relationship/addClient/1FAC2763-9FC0-FC21-4762-42330CEB9P0" . $i;
                $username = $professionalLogin->getUsername();
                $attemptedPassword = 'password';

                $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

                $this->assertTrue($response->body->status === 'success');
            }
        }

        $this->em->refresh($professionalLogin);

        $arrayRels = GetRelationshipFromDB::listRelationship($professionalLogin->getUserID(), $this->CLIENT, $this->em, 'all');

        $this->assertTrue($arrayRels[0][1] === '1FAC2763-9FC0-FC21-4762-42330CEB9P01');
        $this->assertTrue($arrayRels[1][1] === '1FAC2763-9FC0-FC21-4762-42330CEB9P02');
        $this->assertTrue($arrayRels[2][1] === '1FAC2763-9FC0-FC21-4762-42330CEB9P03');
        $this->assertTrue($arrayRels[3][1] === '1FAC2763-9FC0-FC21-4762-42330CEB9P04');
        $this->assertTrue($arrayRels[4][1] === '1FAC2763-9FC0-FC21-4762-42330CEB9P05');

        $relationshipsInitiated = $professionalLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $professionalLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            $this->em->remove($tempRel);
        }
    }

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