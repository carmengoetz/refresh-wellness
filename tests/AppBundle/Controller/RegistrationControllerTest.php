<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\TestCase;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\AppBundle\DatabasePrimer;
use Exception;




/**
 * unit tests for registration controller
 *
 * @version 1.0
 * @author cst233
 */
class RegistrationControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     * @var mixed
     */
    private $em;
    //user to be created from database
    private $userResult;

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
    }

    /**
     * test to make sure user can be created successfully
     */
    public function testCreateUser()
    {
        //create a web client
        $client = static::createClient();

        //go to the registration page
        $crawler = $client->request('GET', '/register');

        //fill out the form with the parameters below
        $form = $crawler->selectButton('Register!')->form(array(

        'user[email]' => 'ISTHISDUDEINTHEDATABASE@gmail.com',
        'user[plainPassword][first]' => 'Password1',
        'user[plainPassword][second]' => 'Password1',
        'user[firstName]' => "Bob",
        'user[lastName]' => "Loblaw",
        'user[birthDate]' => '1969-04-20',
        'user[city]' => 'Saskatoon'
        ));
        $form['user[country]']->select('CA');
        $form['user[termsAccepted]']->tick();

        //submit the form
        $crawler = $client->submit($form);

        //query the database and pull the user created out
        $this->userResult = $this->em
            ->getRepository(User::class)
            ->findBy(['email' => 'ISTHISDUDEINTHEDATABASE@gmail.com']);

        //assert that the user is a user
        $this->assertTrue($this->userResult[0] instanceof User);

    }

    /**
     * tests that the user cannot be created if it already exists based on the email
     */
    public function testUserAlreadyExists()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register!')->form(array(

        'user[email]' => 'cst.project5.refresh+test1@gmail.com',
        'user[plainPassword][first]' => 'Password1',
        'user[plainPassword][second]' => 'Password1',
        'user[firstName]' => "Bob",
        'user[lastName]' => "Loblaw",
        'user[birthDate]' => '1969-04-20',
        'user[city]' => 'Saskatoon'
        ));

        $form['user[country]']->select('CA');
        $form['user[termsAccepted]']->tick();

        $crawler = $client->submit($form);

        $crawler = $client->request('GET', '/register');

        //Create second user with same email
        $form = $crawler->selectButton('Register!')->form(array(

        'user[email]' => 'cst.project5.refresh+test1@gmail.com',
        'user[plainPassword][first]' => 'Password1',
        'user[plainPassword][second]' => 'Password1',
        'user[firstName]' => "Bob",
        'user[lastName]' => "Loblaw",
        'user[birthDate]' => '1969-04-20',
        'user[city]' => 'Saskatoon'
        ));

        $form['user[country]']->select('CA');
        $form['user[termsAccepted]']->tick();

        $crawler = $client->submit($form);


        $this->userResult = $this->em
            ->getRepository(User::class)
            ->findBy(['email' => 'cst.project5.refresh+test1@gmail.com']);

        //assert that the returned array from the database has only 1 element
        $this->assertTrue( count( $this->userResult) == 1);
    }

    /**
     * test that the user cannot be created if the passwords to not match
     */
    public function testUserPasswordNotMatch()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register!')->form(array(

        'user[email]' => 'cst.project5.refresh+test3@gmail.com',
        'user[plainPassword][first]' => 'Password1',
        'user[plainPassword][second]' => 'Password2',
        'user[firstName]' => "Bob",
        'user[lastName]' => "Loblaw",
        'user[birthDate]' => '1969-04-20',
        'user[city]' => 'Saskatoon'
        ));

        $form['user[country]']->select('CA');
        $form['user[termsAccepted]']->tick();

        $crawler = $client->submit($form);

        $this->userResult = $this->em
            ->getRepository(User::class)
            ->findBy(['email' => 'cst.project5.refresh+test3@gmail.com']);

        //assert that the array pulled from the database is empty
        $this->assertTrue(empty($this->userResult));

    }

    /**
     * test that the user cannot be created if the user is too young
     */
    public function testUserTooYoung()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register!')->form(array(

        'user[email]' => 'cst.project5.refresh+test4@gmail.com',
        'user[plainPassword][first]' => 'Password1',
        'user[plainPassword][second]' => 'Password1',
        'user[firstName]' => "Bob",
        'user[lastName]' => "Loblaw",
        'user[birthDate]' => '2013-04-20',
        'user[city]' => 'Saskatoon'
        ));

        $form['user[country]']->select('CA');
        $form['user[termsAccepted]']->tick();

        $crawler = $client->submit($form);

        $this->userResult = $this->em
            ->getRepository(User::class)
            ->findBy(['email' => 'cst.project5.refresh+test4@gmail.com']);

        $this->assertTrue(empty($this->userResult));

    }

    /**
     * test that the user cannot be created if the Strings are too long or too short
     */
    public function testUserStringLength()
    {
        $client = static::createClient();


        for ($i = 0; $i < 6; $i++)
        {
            $string = "B";

            if($i % 2 == 0)
            {
                $string = str_repeat("B",81);
            }

            $crawler = $client->request('GET', '/register');

            $form = $crawler->selectButton('Register!')->form(array(

            'user[email]' => 'cst.project5.refresh+test5@gmail.com',
            'user[plainPassword][first]' => 'Password1',
            'user[plainPassword][second]' => 'Password1',
            'user[firstName]' => $i == 0 || $i == 3 ? $string : "Bob",
            'user[lastName]' => $i == 1 || $i == 4 ? $string :"Loblaw",
            'user[birthDate]' => '1969-04-20',
            'user[city]' => $i == 2 || $i == 5 ? $string :'Saskatoon'
            ));

            $form['user[country]']->select('CA');
            $form['user[termsAccepted]']->tick();

            $crawler = $client->submit($form);

            $this->userResult = $this->em
                ->getRepository(User::class)
                ->findBy(['email' => 'cst.project5.refresh+test5@gmail.com']);

            $this->assertTrue(empty($this->userResult));
        }

    }


    /**
     * test to make sure the user cannot be created if the email is not a valid email
     */
    public function testUserEmailFormat()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

            $form = $crawler->selectButton('Register!')->form(array(

            'user[email]' => 'cst.project5.refresh+test6',
            'user[plainPassword][first]' => 'Password1',
            'user[plainPassword][second]' => 'Password1',
            'user[firstName]' =>"Bob",
            'user[lastName]' => "Loblaw",
            'user[birthDate]' => '1969-04-20',
            'user[city]' => 'Saskatoon'
            ));

            $form['user[country]']->select('CA');
            $form['user[termsAccepted]']->tick();

            $crawler = $client->submit($form);

            $this->userResult = $this->em
                ->getRepository(User::class)
                ->findBy(['email' => 'cst.project5.refresh+test6']);

            $this->assertTrue(empty($this->userResult));

    }

    /**
     * test that the user cannot be created if the user country is invalid
     */
    public function testUserInvalidCountry()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register!')->form(array(

        'user[email]' => 'cst.project5.refresh+test7@gmail.com',
        'user[plainPassword][first]' => 'Password1',
        'user[plainPassword][second]' => 'Password1',
        'user[firstName]' =>"Bob",
        'user[lastName]' => "Loblaw",
        'user[birthDate]' => '1969-04-20',
        'user[city]' => 'Saskatoon'
        ));

        try
        {
        	$form['user[country]']->select('XX');
        }
        catch (Exception $exception)
        {
            $this->assertTrue($exception instanceof Exception);
        }

        //$form['user[termsAccepted]']->tick();

        //$crawler = $client->submit($form);

        //$this->userResult = $this->em
        //    ->getRepository(User::class)
        //    ->findBy(['email' => 'cst.project5.refresh+test7@gmail.com']);

        //$this->assertTrue(empty($this->userResult));


    }

    /**
     * deletes the created user and tears down the database connection
     */
    protected function tearDown()
    {
        if (!empty($this->userResult))
        {
            $this->em->remove($this->userResult[0]);
            $this->em->flush();
        }
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}