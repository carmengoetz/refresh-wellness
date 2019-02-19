<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\TestCase;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Exception;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use AppBundle\Entity\Relationship;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Console\Application;

use AppBundle\DataFixtures\RelationshipFixtures;
use AppBundle\Services\getCountFromDB;
use AppBundle\DataFixtures\AddWPRelFixtures;
use Tests\AppBundle\DatabasePrimer;
use Symfony\Component\Console\Input\ArrayInput;
/**
 * unit tests for relationship controller to add a wellness professional
 *
 * @version 1.0
 * @author cst236
 */
class AddWellnessProfRelationshipControllerTest extends WebTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     * @var mixed
     */
    private $em;
    private $userResult;
    private $MAX_WELLNESS_PROFESSIONALS = 10;
    private $ONE_UNDER_MAX_PROFESSIONALS = 9;
    private $MAX_PROFESSIONAL_PATIENTS = 10;
    private $WELLNESS_PROFESSIONAL = 'wellness professional';

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

        $fixture = new AddWPRelFixtures();
        $fixture->load($this->em);
        $fixture->loadWP($this->em);
        $fixture->addPatientRel($this->em);
    }

    /**
     * test that wellness professional is added
     */
    public function testAddWellnessProfessional()
    {
        $userLogin = $this->em
              ->getRepository(User::class)
              ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CAWPRC7']);

        $url = "http://127.0.0.1/app_test.php/relationship/addWellnessProfessional/1FAC2763-9FC0-FC21-4762-42330CAWPR01";
        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');

        $this->em->refresh($userLogin);

        $this->assertTrue(getCountFromDB::countRelationship($userLogin->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em) == 1);

        $userProf = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CAWPR01']);

        $rel = null;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPR01' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPRC7' )
            {
                $rel = $tempRel;
            }
        }

        $this->assertTrue(isset($rel));
        $this->assertTrue($rel->getType() === 'wellness professional');

        $this->em->refresh($userProf);

        $this->assertTrue(getCountFromDB::countPatients($userProf->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em )== 1);

        $this->em->persist($userProf);
        $this->em->persist($userLogin);

        $this->em->remove($rel);
        $this->em->flush();
    }

    /**
     * test that wellness professional is added up to the max
     */
    public function testAddMaxProfessionals()
    {
        $userLogin = $this->em
              ->getRepository(User::class)
              ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CAWPRC7']);

        //loop to add one less than the max professionals
        for ($i = 1; $i <= $this->ONE_UNDER_MAX_PROFESSIONALS; $i++)
        {
            if($i <= 9)
            {
                $url = "http://127.0.0.1/app_test.php/relationship/addWellnessProfessional/1FAC2763-9FC0-FC21-4762-42330CAWPR0" . $i;
                $username = $userLogin->getUsername();
                $attemptedPassword = 'password';

                $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

                $this->assertTrue($response->body->status === 'success');
            }
        }
        //adding on the last wellness professional
        $url = "http://127.0.0.1/app_test.php/relationship/addWellnessProfessional/1FAC2763-9FC0-FC21-4762-42330CAWPR10";
        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');

        $this->em->refresh($userLogin);

        $this->assertTrue(getCountFromDB::countRelationship($userLogin->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em) == $this->MAX_WELLNESS_PROFESSIONALS);

        $userProf = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CAWPR10']);

        $rel = null;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPR10' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPRC7' )
            {
                $rel = $tempRel;
            }
        }

        $this->assertTrue(isset($rel));
        $this->assertTrue($rel->getType() === 'wellness professional');

        $this->em->refresh($userProf);

        $this->assertTrue(getCountFromDB::countPatients($userProf->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em ) == 1);

        $this->em->persist($userProf);
        $this->em->persist($userLogin);

        foreach ($relationships as $tempRel)
        {
            $this->em->remove($tempRel);
        }

        $this->em->flush();

    }

    /**
     * test that wellness professional is not added to full list
     */
    public function testFullWellnessProfessionals()
    {
        $userLogin = $this->em
              ->getRepository(User::class)
              ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CAWPRC7']);

        //loop to add one less than the max professionals
        for ($i = 1; $i <= $this->MAX_WELLNESS_PROFESSIONALS; $i++)
        {
            if($i == 10)
            {
                $url = "http://127.0.0.1/app_test.php/relationship/addWellnessProfessional/1FAC2763-9FC0-FC21-4762-42330CAWPR" . $i;
                $username = $userLogin->getUsername();
                $attemptedPassword = 'password';

                $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();
                $this->assertTrue($response->body->status === 'success');
            }
            elseif($i <= 9)
            {
                $url = "http://127.0.0.1/app_test.php/relationship/addWellnessProfessional/1FAC2763-9FC0-FC21-4762-42330CAWPR0" . $i;
                $username = $userLogin->getUsername();
                $attemptedPassword = 'password';

                $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();
                $this->assertTrue($response->body->status === 'success');
            }
        }

        $url = "http://127.0.0.1/app_test.php/relationship/addWellnessProfessional/1FAC2763-9FC0-FC21-4762-42330CAWPR11";
        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->reason === 'Max wellness professionals reached.');

        $this->assertTrue(getCountFromDB::countRelationship($userLogin->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em) == $this->MAX_WELLNESS_PROFESSIONALS);

        $this->em->refresh($userLogin);

        //counts the relationships
        $rel = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPR11' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPRC7' )
            {
                $rel = 1;
            }
        }

        $this->assertTrue($rel == 0);

        $this->em->refresh($userLogin);
        $this->em->persist($userLogin);

        foreach ($relationships as $tempRel)
        {
            $this->em->remove($tempRel);
        }

        $this->em->flush();
    }

    /**
     * test that wellness professional is already in list, no change
     */
    public function testAddProfessionalInList()
    {
        $userLogin = $this->em
              ->getRepository(User::class)
              ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CAWPRC7']);

        $url = "http://127.0.0.1/app_test.php/relationship/addWellnessProfessional/1FAC2763-9FC0-FC21-4762-42330CAWPR01";
        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');

        $this->em->refresh($userLogin);

        $this->assertTrue(getCountFromDB::countRelationship($userLogin->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em) == 1);

        $userProf = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CAWPR01']);

        $rel = null;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPR01' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPRC7' )
            {
                $rel = $tempRel;
            }
        }

        $this->assertTrue(isset($rel));
        $this->assertTrue($rel->getType() === 'wellness professional');



        $url = "http://127.0.0.1/app_test.php/relationship/addWellnessProfessional/1FAC2763-9FC0-FC21-4762-42330CAWPR01";
        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->reason == 'Patient relationship already exists');

        $this->em->refresh($userLogin);
        $this->em->refresh($userProf);

        //added result failure message

        $rel = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPR01' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPRC7' )
            {
                $rel = 1;
            }
        }

        $this->assertTrue($rel === 1);

        $this->em->refresh($userLogin);
        $this->em->refresh($userProf);

        $this->assertTrue(getCountFromDB::countPatients($userProf->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em ) == 1);
        $this->assertTrue(getCountFromDB::countRelationship($userLogin->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em ) == 1);

        $this->em->persist($userProf);
        $this->em->persist($userLogin);

        foreach ($relationships as $tempRel)
        {
        	$this->em->remove($tempRel);
        }


        $this->em->flush();
    }


    /**
     * test that wellness professional is not added, no such user
     */
    public function testAddProfessionalNotExists()
    {
        $userLogin = $this->em
              ->getRepository(User::class)
              ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CAWPRC7']);

        //guid does not belong to a real user
        $url = "http://127.0.0.1/app_test.php/relationship/addWellnessProfessional/1FAC2763-9FC0-FC21-4762-12330CAWPRC9";
        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->reason === 'User not found');

        $this->em->refresh($userLogin);

        $this->assertTrue(getCountFromDB::countRelationship($userLogin->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em) == 0);

        $rel = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPRC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPRC7' )
            {
                $rel = 1;
            }
        }

        $this->assertTrue($rel === 0);

    }

    /**
     * test that I cannot add myself as a wellness professional
     */
    public function testAddProfessionalMyself()
    {
        $userLogin = $this->em
              ->getRepository(User::class)
              ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CAWPRC7']);

        //using your own guid
        $url = "http://127.0.0.1/app_test.php/relationship/addWellnessProfessional/1FAC2763-9FC0-FC21-4762-42330CAWPRC7";
        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->reason === 'You cannot add yourself as a wellness professional.');

        $this->em->refresh($userLogin);

        $this->assertTrue(getCountFromDB::countRelationship($userLogin->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em) == 0);

        $rel = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPRC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPRC7' )
            {
                $rel = 1;
            }
        }

        $this->assertTrue($rel === 0);
    }

    /**
     * test that wellness professional with a disabled account is not added
     */
    public function testAddInnactiveProfessional()
    {
        $userLogin = $this->em
              ->getRepository(User::class)
              ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CAWPRC7']);

        $url = "http://127.0.0.1/app_test.php/relationship/addWellnessProfessional/1FAC2763-9FC0-FC21-4762-42330CAWPR16";
        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->reason === 'target user is disabled');

        $this->em->refresh($userLogin);

        $this->assertTrue(getCountFromDB::countRelationship($userLogin->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em) == 0);

        $rel = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPR16' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPRC7' )
            {
                $rel = 1;
            }
        }

        $this->assertTrue($rel === 0);

    }

    /** P
     * Needs updating, has to add max relationships for the professional instead of the patient
     * test that wellness professional is not added, their patient list is full
     */
    public function testAddProfessionalsListFull()
    {
        $userLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CAWPRC7']);

        $url = "http://127.0.0.1/app_test.php/relationship/addWellnessProfessional/1FAC2763-9FC0-FC21-4762-42330CAWPR17";
        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->reason === 'Wellness professionals patient list is full');

        $this->em->refresh($userLogin);

        $this->assertTrue( getCountFromDB::countRelationship($userLogin->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em) == 0);

        $userProf = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CAWPR17']);


        $rel = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPR17' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPRC7' )
            {
                $rel = 1;
            }
        }

        $this->assertTrue($rel === 0);

        $this->em->refresh($userProf);

        $count = getCountFromDB::countPatients($userProf->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em );

        $this->assertTrue($count == 10);

        $this->em->persist($userProf);

        $this->em->flush();
    }

    /**
     * test that wellness professional is added to wellness professional list that is your friend
     */
    public function testAddProfessionalIsFriend()
    {
        $userLogin = $this->em
              ->getRepository(User::class)
              ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CAWPRC7']);

        //adding the wellness professional as a friend
        $url = "http://127.0.0.1/app_test.php/relationship/addFriend/1FAC2763-9FC0-FC21-4762-42330CAWPR01";
        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();
        $this->assertTrue($response->body->status === 'success');

        $url = "http://127.0.0.1/app_test.php/relationship/addWellnessProfessional/1FAC2763-9FC0-FC21-4762-42330CAWPR01";
        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');

        $this->em->refresh($userLogin);

        $this->assertTrue(getCountFromDB::countRelationship($userLogin->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em) == 1);
        $this->assertTrue($userLogin->getNumFriends() == 1);

        $userProf = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CAWPR01']);

        $rel = null;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPR01' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPRC7' && $tempRel->getType != 'friend' )
            {
                $rel = $tempRel;
            }
        }

        $this->assertTrue(isset($rel));
        $this->assertTrue($rel->getType() === 'wellness professional');

        $this->em->refresh($userProf);

        $this->assertTrue(getCountFromDB::countPatients($userProf->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em ) == 1);

        $this->em->persist($userProf);
        $this->em->persist($userLogin);

        foreach ($relationships as $tempRel)
        {
            $this->em->remove($tempRel);
        }

        $this->em->remove($rel);
        $this->em->flush();
    }

    /** P
     * test that a normal user cannot be added as a wellness professional.
     */
    public function testAddProfessionalNormalUser()
    {
        $userLogin = $this->em
              ->getRepository(User::class)
              ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CAWPRC7']);

        $url = "http://127.0.0.1/app_test.php/relationship/addWellnessProfessional/1FAC2763-9FC0-FC21-4762-42330CAWPRC6";
        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');
        $this->assertTrue($response->body->reason === 'User is not a qualified wellness professional.');

        $this->em->refresh($userLogin);

        $this->assertTrue(getCountFromDB::countRelationship($userLogin->getUserID(), $this->WELLNESS_PROFESSIONAL, $this->em) == 0);

        $rel = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {

            if ($tempRel->getType() === "wellness professional" && $tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPRC6' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CAWPRC7' )
            {
                $rel = 1;
            }
        }

        $this->assertTrue($rel === 0);

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
}