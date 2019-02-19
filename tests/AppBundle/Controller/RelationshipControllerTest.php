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
use Tests\AppBundle\DatabasePrimer;
use Symfony\Component\Console\Input\ArrayInput;

use AppBundle\DataFixtures\RelationshipFixtures;



/**
 * unit tests for registration controller
 *
 * @version 1.0
 * @author cst233
 */
class RelationshipControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     * @var mixed
     */
    private $em;
    //user to be created from database
    private $userResult;
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

        $fixture = new RelationshipFixtures();
        $fixture->load($this->em);
    }


    /**
     * test to make sure friend can be added successfully
     */
    public function testAddFriend()
    {
        $userLogin = $this->em
              ->getRepository(User::class)
              ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);

        $username = 'cst.project5.refresh+test1@gmail.com';
        $attemptedPassword = 'password';


        $url = "http://127.0.0.1/app_test.php/relationship/addFriend/1FAC2763-9FC0-FC21-4762-42330CEB9BC6";
        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');

        $this->em->refresh($userLogin);

        $this->assertTrue($userLogin->getNumFriends() == 1);

        $userFriend = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6']);


        //REPLACES A QUERY FOR THE RELATIONSHIP
        $rel = null;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);



        foreach ($relationships as $tempRel)
        {


        	if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' )
            {
                $rel = $tempRel;
            }
        }

        $this->assertTrue(isset($rel));

        $this->assertTrue($rel->getType() === 'friend');

        //***********************************


        //$rel = $this->em
        //     ->getRepository(Relationship::class)
        //     ->findOneBy(array('' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7:1FAC2763-9FC0-FC21-4762-42330CEB9BC6:friend', 'type'=> 'friend'));

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
     * Tests that a friend can be added when the user has almost full friends list
     */
    public function testAddFriendNearMax()
    {

        $userLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);

        $userFriend = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6']);


        $userLogin->setNumFriends(99);

        $this->assertTrue($userLogin->getNumFriends() === 99);

        $this->em->flush();

        $url = "http://127.0.0.1/app_test.php/relationship/addFriend/1FAC2763-9FC0-FC21-4762-42330CEB9BC6";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->em->refresh($userLogin);

        $this->assertTrue($response->body->status === 'success');

        //REPLACES A QUERY FOR THE RELATIONSHIP
        $rel = null;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);



        foreach ($relationships as $tempRel)
        {


        	if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' )
            {
                $rel = $tempRel;
            }
        }

        $this->assertTrue(isset($rel));

        $this->assertTrue($rel->getType() === 'friend');

        //***********************************


        $this->em->refresh($userFriend);

        $this->em->refresh($userLogin);

        $this->assertTrue($userLogin->getNumFriends() == 100);
        $this->assertTrue($userFriend->getNumFriends() == 1);

        $userFriend->setNumFriends(0);
        $this->em->persist($userFriend);


        $userLogin->setNumFriends(0);
        $this->em->persist($userLogin);
        $this->em->remove($rel);
        $this->em->flush();



    }


    /**
     * Makes sure a friend can't be added twice
     */
    public function testAddFriendAlreadyFriend()
    {

        $userLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);

        $userFriend = $this->em
         ->getRepository(User::class)
         ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6']);


        $url = "http://127.0.0.1/app_test.php/relationship/addFriend/1FAC2763-9FC0-FC21-4762-42330CEB9BC6";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'success');
        $this->em->refresh($userFriend);

        $this->em->refresh($userLogin);
        //REPLACES A QUERY FOR THE RELATIONSHIP
        $rel = null;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);



        foreach ($relationships as $tempRel)
        {


        	if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' )
            {
                $rel = $tempRel;
            }
        }

        $this->assertTrue(isset($rel));

        $this->assertTrue($rel->getType() === 'friend');

        //***********************************

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);


        $this->assertTrue($userLogin->getNumFriends() == 1);

        $this->assertTrue($userFriend->getNumFriends() == 1);


        $url = "http://127.0.0.1/app_test.php/relationship/addFriend/1FAC2763-9FC0-FC21-4762-42330CEB9BC6";
        $response = \Httpful\Request::get($url)->send();

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);


        //REPLACES A QUERY FOR THE RELATIONSHIP
        $relCount = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);



        foreach ($relationships as $tempRel)
        {


        	if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'friend' )
            {
                $relCount++;
            }
        }

        $this->assertTrue($relCount === 1);


        //***********************************
        //Check relationship other direction


        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);


        $this->assertTrue($userLogin->getNumFriends() == 1);

        $this->assertTrue($userFriend->getNumFriends() == 1);


        $url = "http://127.0.0.1/app_test.php/relationship/addFriend/1FAC2763-9FC0-FC21-4762-42330CEB9BC7";
        $username = $userFriend->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);


        //REPLACES A QUERY FOR THE RELATIONSHIP
        $relCount = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);



        foreach ($relationships as $tempRel)
        {


        	if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'friend' )
            {
                $relCount++;
            }
        }

        $this->assertTrue($relCount === 1);


        //***********************************



        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $this->assertTrue($userLogin->getNumFriends() == 1);
        $this->assertTrue($userFriend->getNumFriends() == 1);

        $userFriend->setNumFriends(0);
        $this->em->persist($userFriend);

        $userLogin->setNumFriends(0);
        $this->em->persist($userLogin);

        $this->em->remove($rel);
        $this->em->flush();


    }
    /**
     * Makes sure a user who doesn't exist can't be added as a friend
     */
    public function testAddFriendDoesntExist()
    {
        $userLogin = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);

        $url = "http://127.0.0.1/app_test.php/relationship/addFriend/1FAC2763-9FC0-FC21-4762-42330CEB9BC5";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');

        $this->em->refresh($userLogin);
        //REPLACES A QUERY FOR THE RELATIONSHIP
        $relCount = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

            foreach ($relationships as $tempRel)
            {


                if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC5' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'friend' )
                {
                    $relCount++;
                }
            }

        $this->assertTrue($relCount === 0);

        //***********************************

        $this->em->refresh($userLogin);

        $this->assertTrue($userLogin->getNumFriends() == 0);
    }


    /**
     * Makes sure you can't add yourself as a friend
     */
    public function testAddFriendMyself()
    {
        $userLogin = $this->em
         ->getRepository(User::class)
         ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);
        $url = "http://127.0.0.1/app_test.php/relationship/addFriend/1FAC2763-9FC0-FC21-4762-42330CEB9BC7";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');

        $this->em->refresh($userLogin);
        //REPLACES A QUERY FOR THE RELATIONSHIP
        $relCount = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {

            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'friend' )
            {
                $relCount++;
            }
        }

        $this->assertTrue($relCount === 0);

        //***********************************
        $this->em->refresh($userLogin);

        $this->assertTrue($userLogin->getNumFriends() == 0);

    }


    /**
     * Makes sure you can't add an inactive user as a friend
     */
    public function testAddFriendInactive()
    {
        $userLogin = $this->em
        ->getRepository(User::class)
        ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);
        $url = "http://127.0.0.1/app_test.php/relationship/addFriend/1FAC2763-9FC0-FC21-4762-42330CEB9BC8";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');

        $this->em->refresh($userLogin);

        //REPLACES A QUERY FOR THE RELATIONSHIP
        $relCount = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {

            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC8' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'friend' )
            {
                $relCount++;
            }
        }

        $this->assertTrue($relCount === 0);

        //***********************************
        $this->em->refresh($userLogin);


        $this->assertTrue($userLogin->getNumFriends() == 0);

    }
    /**
     * Makes sure you can't add a friend when the friends list is full
     */
    public function testAddFriendFullList()
    {
        $userLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);

        $userFriend = $this->em
           ->getRepository(User::class)
           ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6']);

        $userLogin->setNumFriends(100);

        $this->assertTrue($userLogin->getNumFriends() === 100);

        $this->em->flush();

        $url = "http://127.0.0.1/app_test.php/relationship/addFriend/1FAC2763-9FC0-FC21-4762-42330CEB9BC6";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');


        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);
        //REPLACES A QUERY FOR THE RELATIONSHIP
        $relCount = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'friend' )
            {
                $relCount++;
            }
        }

        $this->assertTrue($relCount === 0);

        //***********************************

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $this->assertTrue($userFriend->getNumFriends() == 0);
        $this->em->refresh($userLogin);
        $this->assertTrue($userLogin->getNumFriends() == 100);

        $userFriend->setNumFriends(0);
        $userLogin->setNumFriends(0);

        $this->em->flush();

    }

    /**
     * test to make sure supporter can be added
     */
    public function testAddSupporter()
    {
        $userLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6']);

        $userFriend = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);
        $url = "http://127.0.0.1/app_test.php/relationship/addSupporter/1FAC2763-9FC0-FC21-4762-42330CEB9BC7";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $this->assertTrue($response->body->status === 'success');


        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $this->assertTrue($userLogin->getNumSupporters() == 1);

        $this->assertTrue($userFriend->getNumSupportees() == 1);

        //REPLACES A QUERY FOR THE RELATIONSHIP
        $rel = null;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {

            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'support' )
            {
                $rel = $tempRel;
            }
        }


        $this->assertTrue(isset($rel));

        //***********************************


        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $userFriend->setNumSupportees(0);
        $this->em->persist($userFriend);

        $userLogin->setNumSupporters(0);
        $this->em->persist($userLogin);

        $this->em->remove($rel);
        $this->em->flush();
    }

    /**
     * Makes usre a user does not have to be a friend to be a supporter
     */
    public function testAddSupporterNotFriend()
    {
        $userLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6']);

        $userFriend = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);
        //try and find a existing friendship relationship for the users.
        //REPLACES A QUERY FOR THE RELATIONSHIP
        $relCount = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getType() === 'friend' )
            {
                $relCount++;
            }
        }

        $this->assertTrue($relCount === 0);

        //***********************************

        //This line is currently breaking this (says relationship already exists???)
        $url = "http://127.0.0.1/app_test.php/relationship/addSupporter/1FAC2763-9FC0-FC21-4762-42330CEB9BC7";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();
        sleep(1);
        $this->assertTrue($response->body->status === 'success');

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        //REPLACES A QUERY FOR THE RELATIONSHIP
        $rel = null;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        foreach ($relationships as $tempRel)
        {

            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getType() === 'support' )
            {
                $rel = $tempRel;
            }
        }

        $this->assertTrue(isset($rel));

        //***********************************


        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $this->assertTrue($userLogin->getNumSupporters() == 1);

        $this->assertTrue($userFriend->getNumSupportees() == 1);

        $userFriend->setNumSupportees(0);
        $this->em->persist($userFriend);

        $userLogin->setNumSupporters(0);
        $this->em->persist($userLogin);

        $this->em->remove($rel);
        $this->em->flush();
    }
    /**
     * Makes sure a user can be a friend and also a supporter
     */
    public function testAddSupporterFriend()
    {
        $userLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6']);

        $userFriend = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);
        $relFriend = null;
        $relSupport = null;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        $relCount = 0;
        foreach ($relationships as $tempRel)
        {

            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getType() === 'friend' )
            {
                $relFriend = $tempRel;
                $relCount++;
            }
        }

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);
        if($relCount == 0){
            $url = "http://127.0.0.1/app_test.php/relationship/addFriend/1FAC2763-9FC0-FC21-4762-42330CEB9BC7";

            $username = $userLogin->getUsername();
            $attemptedPassword = 'password';

            $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

            $this->em->refresh($userLogin);
            $this->em->refresh($userFriend);

            $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
            $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

            $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);


            foreach ($relationships as $tempRel)
            {

                if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getType() === 'friend' )
                {
                    $relFriend = $tempRel;
                }
            }
        }
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);
        //add the supporter not that there is a friendship
        $url = "http://127.0.0.1/app_test.php/relationship/addSupporter/1FAC2763-9FC0-FC21-4762-42330CEB9BC7";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        sleep(1);

        $this->assertTrue($response->body->status === 'success');


        //get the relationship (see if its there)
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);
        foreach ($relationships as $tempRel)
        {


            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getType() === 'support' )
            {
                $relSupport = $tempRel;
            }
        }

        $this->assertTrue($relSupport->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $relSupport->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $relSupport->getType() ==='support');

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $this->em->refresh($userLogin);
        $this->assertTrue($userLogin->getNumSupporters() == 1);

        $this->em->refresh($userFriend);
        $this->assertTrue($userFriend->getNumSupportees() == 1);

        $userFriend->setNumFriends(0);
        $userFriend->setNumSupportees(0);
        $this->em->persist($userFriend);

        $userLogin->setNumFriends(0);
        $userLogin->setNumSupporters(0);
        $this->em->persist($userLogin);

        $this->em->remove($relSupport);
        $this->em->remove($relFriend);
        $this->em->flush();

    }
    /**
     * Makes sure you can add a supporter when you have almost full supporter lists
     */
    public function testAddSupporterNearMax()
    {
        //get the first user and increas thier supporter amount
        $userLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6']);

        $userFriend = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);

        $userLogin->setNumSupporters(4);

        $this->assertTrue($userLogin->getNumSupporters() === 4);

        $this->em->flush();
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);
        //add first support role
        $url = "http://127.0.0.1/app_test.php/relationship/addSupporter/1FAC2763-9FC0-FC21-4762-42330CEB9BC7";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();
        sleep(1);
        $this->assertTrue($response->body->status === 'success');


        //refresh users and make sure there was a relationship

        $relSupport = null;
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);


        foreach ($relationships as $tempRel)
        {


            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getType() === 'support' )
            {
                $relSupport = $tempRel;
            }
        }


        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);
        $this->assertTrue($relSupport->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $relSupport->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $relSupport->getType() ==='support');


        $this->em->refresh($userFriend);

        $this->em->refresh($userLogin);

        //refresh user and check supporters
        $this->assertTrue($userLogin->getNumSupporters() == 5);

        $this->assertTrue($userFriend->getNumSupportees() == 1);

        $userFriend->setNumSupportees(0);
        $this->em->persist($userFriend);

        $userLogin->setNumSupporters(0);
        $this->em->persist($userLogin);

        $this->em->remove($relSupport);
        $this->em->flush();

    }


    /**
     * Makes sure a supporter can't be added more than once
     */
    public function testAddSupporterAlreadySupporter()
    {

        $userLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6']);

        $userFriend = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $url = "http://127.0.0.1/app_test.php/relationship/addSupporter/1FAC2763-9FC0-FC21-4762-42330CEB9BC7";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();
        sleep(1);
        $this->assertTrue($response->body->status === 'success');

        $relSupport = null;

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);


        foreach ($relationships as $tempRel)
        {


            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getType() === 'support' )
            {
                $relSupport = $tempRel;
            }
        }

        $this->assertTrue($relSupport->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $relSupport->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $relSupport->getType() ==='support');

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $this->assertTrue($userLogin->getNumSupporters() == 1);
        $this->assertTrue($userFriend->getNumSupportees() == 1);

        //try and add again


        $url = "http://127.0.0.1/app_test.php/relationship/addSupporter/1FAC2763-9FC0-FC21-4762-42330CEB9BC7";
        $response = \Httpful\Request::get($url)->send();
        sleep(1);
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        $relCount = 0;
        foreach ($relationships as $tempRel)
        {


            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getType() === 'support' )
            {
                $relSupport = $tempRel;
                $relCount++;
            }
        }

        $this->assertTrue($relCount == 1);

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $this->assertTrue($userLogin->getNumSupporters() == 1);
        $this->assertTrue($userFriend->getNumSupportees() == 1);

        $userFriend->setNumSupportees(0);
        $this->em->persist($userFriend);

        $userLogin->setNumSupporters(0);
        $this->em->persist($userLogin);

        $this->em->remove($relSupport);
        $this->em->flush();

    }
    /**
     * Makes sure you can't add a supporter who doesn't exist
     */
    public function testAddSupporterDoesntExist()
    {
        $userLogin = $this->em
        ->getRepository(User::class)
        ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);
        $url = "http://127.0.0.1/app_test.php/relationship/addSupporter/1FAC2763-9FC0-FC21-4762-42330CEB9BC5";
        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->em->refresh($userLogin);

        $this->assertTrue($response->body->status === 'failure');

        //REPLACES A QUERY FOR THE RELATIONSHIP
        $relCount = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);


        foreach ($relationships as $tempRel)
        {


            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC5' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'support' )
            {
                $relCount++;
            }
        }
        $this->em->refresh($userLogin);

        $this->assertTrue($relCount === 0);


        //***********************************

        $this->em->refresh($userLogin);

        $this->assertTrue($userLogin->getNumSupporters() == 0);

    }
    /**
     * Makes sure you can't add yourself as a supporter
     */
    public function testAddMyselfAsSupporter()
    {
        $userLogin = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);
        $url = "http://127.0.0.1/app_test.php/relationship/addSupporter/1FAC2763-9FC0-FC21-4762-42330CEB9BC7";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');


        $this->em->refresh($userLogin);

        //REPLACES A QUERY FOR THE RELATIONSHIP
        $relCount = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);


        foreach ($relationships as $tempRel)
        {


            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'support' )
            {
                $relCount++;
            }
        }


        $this->assertTrue($relCount === 0);


        //***********************************

        $this->em->refresh($userLogin);

        $this->assertTrue($userLogin->getNumSupporters() == 0);

    }

     /** Makes sure you can't add an inactive user as a supporter
     */
    public function testAddSupporterInactive()
    {
        $userLogin = $this->em
        ->getRepository(User::class)
        ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);
        $url = "http://127.0.0.1/app_test.php/relationship/addSupporter/1FAC2763-9FC0-FC21-4762-42330CEB9BC8";
        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');


        $this->em->refresh($userLogin);

        //REPLACES A QUERY FOR THE RELATIONSHIP
        $relCount = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);


        $this->em->refresh($userLogin);

        foreach ($relationships as $tempRel)
        {


            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC8' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'supportee' )
            {
                $relCount++;
            }
        }


        $this->assertTrue($relCount === 0);


        $this->em->refresh($userLogin);
        //***********************************



        $this->assertTrue($userLogin->getNumSupporters() == 0);

    }
    /**
     * Makes sure you can add a supportee if you aren't their friend
     */
    public function testAddSupporteeNotFriend()
    {
        $userLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);


        $userFriend = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6']);

        //try and find a existing friendship relationship for the users.
        //REPLACES A QUERY FOR THE RELATIONSHIP
        $relCount = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        foreach ($relationships as $tempRel)
        {


            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'friend' )
            {
                $relCount++;
            }
        }
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $this->assertTrue($relCount === 0);


        //***********************************

        //This line is currently breaking this (says relationship already exists???)
        $url = "http://127.0.0.1/app_test.php/relationship/addSupportee/1FAC2763-9FC0-FC21-4762-42330CEB9BC6";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();
        sleep(1);

        $this->assertTrue($response->body->status === 'success');

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);


        //REPLACES A QUERY FOR THE RELATIONSHIP
        $rel = null;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        foreach ($relationships as $tempRel)
        {


            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getType() === 'support' )
            {
                $rel = $tempRel;
            }
        }


        $this->assertTrue(isset($rel));

        //***********************************

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);


        $this->assertTrue($userLogin->getNumSupportees() == 1);

        $this->assertTrue($userFriend->getNumSupporters() == 1);

        $userFriend->setNumSupporters(0);
        $this->em->persist($userFriend);

        $userLogin->setNumSupportees(0);
        $this->em->persist($userLogin);

        $this->em->remove($rel);
        $this->em->flush();

    }
    /**
     * Makes sure you can add a supportee if you are their friend
     */
    public function testAddSupporteeFriend()
    {
        $userLogin = $this->em
           ->getRepository(User::class)
           ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);

        $userFriend = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6']);

        $relFriend = null;
        $relSupport = null;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $relCount = 0;
        foreach ($relationships as $tempRel)
        {


            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'friend' )
            {
                $relFriend = $tempRel;
                $relCount++;
            }
        }
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        if($relCount == 0){
            $url = "http://127.0.0.1/app_test.php/relationship/addFriend/1FAC2763-9FC0-FC21-4762-42330CEB9BC6";
            $username = $userLogin->getUsername();
            $attemptedPassword = 'password';

            $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();
            sleep(1);
            $this->em->refresh($userLogin);
            $this->em->refresh($userFriend);



            $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
            $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

            $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);


            foreach ($relationships as $tempRel)
            {


                if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'friend' )
                {
                    $relFriend = $tempRel;
                }
            }
        }
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);
        //add the supporter not that there is a friendship
        $url = "http://127.0.0.1/app_test.php/relationship/addSupportee/1FAC2763-9FC0-FC21-4762-42330CEB9BC6";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();
        sleep(1);
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);
        $this->assertTrue($response->body->status === 'success');


        //get the relationship (see if its there)
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);


        foreach ($relationships as $tempRel)
        {


            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getType() === 'support' )
            {
                $relSupport = $tempRel;
            }
        }

        $this->assertTrue($relSupport->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $relSupport->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $relSupport->getType() ==='support');


        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);
        $this->em->refresh($userLogin);
        $this->assertTrue($userLogin->getNumSupportees() == 1);




        $this->em->refresh($userFriend);
        $this->assertTrue($userFriend->getNumSupporters() == 1);



        $userFriend->setNumFriends(0);
        $userFriend->setNumSupporters(0);
        $this->em->persist($userFriend);

        $userLogin->setNumFriends(0);
        $userLogin->setNumSupportees(0);
        $this->em->persist($userLogin);

        $this->em->remove($relSupport);
        $this->em->remove($relFriend);
        $this->em->flush();

    }
         /**
     * Makes sure you can't add the same supportee twice
     */
    public function testAddSupporteeAlreadyExists()
    {
        $userLogin = $this->em
           ->getRepository(User::class)
           ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);

        $userFriend = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6']);


        $url = "http://127.0.0.1/app_test.php/relationship/addSupportee/1FAC2763-9FC0-FC21-4762-42330CEB9BC6";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);
        $this->assertTrue($response->body->status === 'success');

        $relSupport = null;

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        foreach ($relationships as $tempRel)
        {
            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getType() === 'support' )
            {
                $relSupport = $tempRel;
            }
        }

        $this->assertTrue($relSupport->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $relSupport->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $relSupport->getType() ==='support');

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $this->assertTrue($userLogin->getNumSupportees() == 1);
        $this->assertTrue($userFriend->getNumSupporters() == 1);

        //try and add again


        $url = "http://127.0.0.1/app_test.php/relationship/addSupportee/1FAC2763-9FC0-FC21-4762-42330CEB9BC6";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();
        sleep(1);
        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        $relCount = 0;
        foreach ($relationships as $tempRel)
        {


            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getType() === 'support' )
            {
                $relSupport = $tempRel;
                $relCount++;
            }
        }
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);
        $this->assertTrue($relCount == 1);

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $this->assertTrue($userLogin->getNumSupportees() == 1);
        $this->assertTrue($userFriend->getNumSupporters() == 1);

        $userFriend->setNumSupporters(0);
        $this->em->persist($userFriend);

        $userLogin->setNumSupportees(0);
        $this->em->persist($userLogin);

        $this->em->remove($relSupport);
        $this->em->flush();
    }
         /**
     * Makes sure you can't add a non-existant user as a supportee
     */
    public function testAddSupporteeNotExist()
    {
        $userLogin = $this->em
        ->getRepository(User::class)
        ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);
        $url = "http://127.0.0.1/app_test.php/relationship/addSupportee/1FAC2763-9FC0-FC21-4762-42330CEB9BC5";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');

        //REPLACES A QUERY FOR THE RELATIONSHIP
        $relCount = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);
        $this->em->refresh($userLogin);

        foreach ($relationships as $tempRel)
        {


            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC5' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'support' )
            {
                $relCount++;
            }
        }


        $this->assertTrue($relCount === 0);


        //***********************************

        $this->em->refresh($userLogin);

        $this->assertTrue($userLogin->getNumSupportees() == 0);

    }
     /**
     * Makes sure you can't add yourself as a supportee
     */
    public function testAddSupporteeMyself()
    {
        $userLogin = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);
        $url = "http://127.0.0.1/app_test.php/relationship/addSupportee/1FAC2763-9FC0-FC21-4762-42330CEB9BC7";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');

        //REPLACES A QUERY FOR THE RELATIONSHIP
        $relCount = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);
        $this->em->refresh($userLogin);

        foreach ($relationships as $tempRel)
        {

            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'support' )
            {
                $relCount++;
            }
        }

        $this->assertTrue($relCount === 0);

        //***********************************

        $this->em->refresh($userLogin);

        $this->assertTrue($userLogin->getNumSupportees() == 0);
    }

         /**
     * Makse sure you can't add an inactive user as supportee
     */
    public function testAddSupporteeInactive()
    {
        $userLogin = $this->em
          ->getRepository(User::class)
          ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);
        $url = "http://127.0.0.1/app_test.php/relationship/addSupportee/1FAC2763-9FC0-FC21-4762-42330CEB9BC8";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $this->assertTrue($response->body->status === 'failure');

        $this->em->refresh($userLogin);

        //REPLACES A QUERY FOR THE RELATIONSHIP
        $relCount = 0;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);
        $this->em->refresh($userLogin);


        foreach ($relationships as $tempRel)
        {


            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC8' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'supportee' )
            {
                $relCount++;
            }
        }


        $this->assertTrue($relCount === 0);


        //***********************************
        $this->em->refresh($userLogin);


        $this->assertTrue($userLogin->getNumSupportees() == 0);
    }

    /**
     * Makes sure a user can be a supportee and also a supporter
     */
    public function testAddSupporterAlreadySupportee()
    {
        $userLogin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6']);

        $userFriend = $this->em
            ->getRepository(User::class)
            ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7']);

        $relFriend = null;
        $relSupport = null;

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);
        $relCount = 0;
        foreach ($relationships as $tempRel)
        {


            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getType() === 'support' )
            {
                $relFriend = $tempRel;
                $relCount++;
            }
        }


        if($relCount == 0){
            $url = "http://127.0.0.1/app_test.php/relationship/addSupportee/1FAC2763-9FC0-FC21-4762-42330CEB9BC7";

            $username = $userLogin->getUsername();
            $attemptedPassword = 'password';

            $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();
            sleep(1);
            $this->em->refresh($userLogin);
            $this->em->refresh($userFriend);

            $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
            $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

            $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);

            $this->em->refresh($userLogin);
            $this->em->refresh($userFriend);
            foreach ($relationships as $tempRel)
            {

                if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getType() === 'support' )
                {
                    $relFriend = $tempRel;
                }
            }
        }

        //add the supporter not that there is a friendship
        $url = "http://127.0.0.1/app_test.php/relationship/addSupporter/1FAC2763-9FC0-FC21-4762-42330CEB9BC7";

        $username = $userLogin->getUsername();
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();
        sleep(1);
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $this->assertTrue($response->body->status === 'success');


        //get the relationship (see if its there)
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $relationshipsInitiated = $userLogin->relationshipsInitiated->getValues();
        $relationshipsRequested = $userLogin->relationshipsRequested->getValues();

        $relationships = array_merge($relationshipsInitiated,$relationshipsRequested);
        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        foreach ($relationships as $tempRel)
        {

            if ($tempRel->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $tempRel->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $tempRel->getType() === 'support' )
            {
                $relSupport = $tempRel;
            }
        }

        $this->assertTrue($relSupport->getUserIdOne()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC6' && $relSupport->getUserIdTwo()->getUserId() === '1FAC2763-9FC0-FC21-4762-42330CEB9BC7' && $relSupport->getType() ==='support');

        $this->em->refresh($userLogin);
        $this->em->refresh($userFriend);

        $this->em->refresh($userLogin);
        $this->assertTrue($userLogin->getNumSupporters() == 1);

        $this->em->refresh($userFriend);
        $this->assertTrue($userFriend->getNumSupportees() == 1);

        $userFriend->setNumSupporters(0);
        $userFriend->setNumSupportees(0);
        $this->em->persist($userFriend);

        $userLogin->setNumSupportees(0);
        $userLogin->setNumSupporters(0);
        $this->em->persist($userLogin);


        $this->em->flush();

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