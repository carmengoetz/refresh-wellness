<?php
namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Wellness;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\DataFixtures\RelationshipFixtures;
use AppBundle\DataFixtures\WellnessFixtures;
use Tests\AppBundle\DatabasePrimer;
/**
 * WellnessRepositoryTest short summary.
 *
 * WellnessRepositoryTest description.
 *
 * @version 1.0
 * @author cst231
 */
class WellnessControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    private $record;

    /**
     * Setup for testing. connecting to database
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        //Apply the primer
        DatabasePrimer::prime(self::$kernel);

        //Set entity manager
        $this->em = DatabasePrimer::$entityManager;

        $fixture = new WellnessFixtures();
        $fixture->load($this->em);
    }

    /**
     * testing that valid data gets inserted to the database correctly
     */
    public function testValidData()
    {
        $wellnessJSON = array(
            "mood" => 5,
            "energy" => 5,
            "sleep" => 5,
            "thoughts" => 5
        );

        $url = "localhost/app_test.php/howareyoufeeling/submitanswers/" . urlencode(json_encode($wellnessJSON));

        $username = 'cst.project5.refresh+WellnessRecord@gmail.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $id = $response->body->wellnessId;

        //make sure the JSON response has a status of "success"
        $this->assertEquals("success", $response->body->status);

        //look in database for entry in wellness table for the record that was just entered
        $wellness = $this->em->getRepository(Wellness::class);
        //TODO: add an id that actually exists
        $this->record = $wellness->findOneBy(array('id' => $id));

        //make sure the object found above for the wellness record contains the correct attribute values
        $this->assertAttributeEquals($wellnessJSON['mood'], 'mood', $this->record);
        $this->assertAttributeEquals($wellnessJSON['sleep'], 'sleep', $this->record);
        $this->assertAttributeEquals($wellnessJSON['energy'], 'energy', $this->record);
        $this->assertAttributeEquals($wellnessJSON['thoughts'], 'thoughts', $this->record);

    }

    /**
     * testing for values of zero, should not be entered into the database
     */
    public function testInvalidDataMoodZero()
    {
        $wellnessJSON = array(
            "mood" => 0,
            "energy" => 0,
            "sleep" => 0,
            "thoughts" => 0
        );

        $url = "localhost/app_test.php/howareyoufeeling/submitanswers/" . urlencode(json_encode($wellnessJSON));
        $username = 'cst.project5.refresh+WellnessRecord@gmail.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $id = $response->body->wellnessId;

        //make sure the JSON response has a status of "failure"
        $this->assertEquals("failure", $response->body->status);

        //look in database for entry in wellness table for a record with the attempted values
        $wellness = $this->em->getRepository(Wellness::class);
        //TODO: add an id that actually exists
        $this->record = $wellness->findOneBy(array('id' => $id));

        //make sure the object found above for the wellness record is null (was not created)
        $this->assertEquals(null, $this->record);


    }

    /**
     * testing for values below zero, should not be entered into the database
     */
    public function testInvalidMoodDataNeg()
    {
        $wellnessJSON = array(
            "mood" => -1,
            "energy" => -1,
            "sleep" => -1,
            "thoughts" => -1
        );

        $url = "localhost/app_test.php/howareyoufeeling/submitanswers/" . urlencode(json_encode($wellnessJSON));
        $username = 'cst.project5.refresh+WellnessRecord@gmail.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $id = $response->body->wellnessId;

        //make sure the JSON response has a status of "failure"
        $this->assertEquals("failure", $response->body->status);

        //look in database for entry in wellness table for a record with the attempted values
        $wellness = $this->em->getRepository(Wellness::class);
        //TODO: add an id that actually exists
        $this->record = $wellness->findOneBy(array('id' => $id));

        //make sure the object found above for the wellness record is null (was not created)
        $this->assertEquals(null, $this->record);

    }

    /**
     * testing for values above 10, should not be entered into the database
     */
    public function testInvalidMoodDataHigh()
    {
        $wellnessJSON = array(
            "mood" => 11,
            "energy" => 11,
            "sleep" => 11,
            "thoughts" => 11
        );

        $url = "localhost/app_test.php/howareyoufeeling/submitanswers/" . urlencode(json_encode($wellnessJSON));
        $username = 'cst.project5.refresh+WellnessRecord@gmail.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $id = $response->body->wellnessId;

        //make sure the JSON response has a status of "failure"
        $this->assertEquals("failure", $response->body->status);

        //look in database for entry in wellness table for a record with the attempted values
        $wellness = $this->em->getRepository(Wellness::class);
        //TODO: add an id that actually exists
        $this->record = $wellness->findOneBy(array('id' => $id));

        //make sure the object found above for the wellness record is null (was not created)
        $this->assertEquals(null, $this->record);


    }

    /**
     * testing for values not integer, should not be entered into the database
     */
    public function testInvalidMoodDataString()
    {
        $wellnessJSON = array(
            "mood" => "Five",
            "energy" => "Five",
            "sleep" => "Five",
            "thoughts" => "Five"
        );

        $url = "localhost/app_test.php/howareyoufeeling/submitanswers/" . urlencode(json_encode($wellnessJSON));
        $username = 'cst.project5.refresh+WellnessRecord@gmail.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        $id = $response->body->wellnessId;

        //make sure the JSON response has a status of "failure"
        $this->assertEquals("failure", $response->body->status);

        //look in database for entry in wellness table for a record with the attempted values
        $wellness = $this->em->getRepository(Wellness::class);
        //TODO: add an id that actually exists
        $this->record = $wellness->findOneBy(array('id' => $id));

        //make sure the object found above for the wellness record is null (was not created)
        $this->assertEquals(null, $this->record);


    }

    /**
     * deletes the created wellness object and tears down the database connection
     */
    protected function tearDown()
    {

        if (!empty($this->record))
        {
            $this->em->remove($this->record);
            $this->em->flush();
        }
        $fixture = new WellnessFixtures();
        $fixture->unload($this->em);


        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}

