<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\DataFixtures\RelationshipFixtures;
use AppBundle\DataFixtures\ViewWellnessQFixtures;
use Tests\AppBundle\DatabasePrimer;
/**
 * ViewWellnessQuestionTest short summary.
 *
 * ViewWellnessQuestionTest description.
 *
 * @version 1.0
 * @author cst231
 */
class ViewWellnessQuestionControllerTest extends WebTestCase
{
    private $em;
    /**
     * Setup
     */
    public function setUp()
    {
        self::bootKernel();

        //Apply the primer
        DatabasePrimer::prime(self::$kernel);

        //Set entity manager
        $this->em = DatabasePrimer::$entityManager;

        $fixture = new ViewWellnessQFixtures();
        $fixture->load($this->em);
    }
    /**
     *
     * Tear down
     */
    public function tearDown()
    {
        $fixture = new ViewWellnessQFixtures();
        $fixture->unload($this->em);


        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }

    /**
     *
     * Tests that the response coming back has the data needed
     */
    public function testViewWellnessQuestions()
    {
        //Set url of page to go to
        $url = "localhost/app_test.php/howareyoufeeling/view";
        //Send post request to that page
        $username = 'viewWellnessQuestionsController@userone.com';
        $attemptedPassword = 'password';

        $response = \Httpful\Request::get($url)->basicAuth($username, $attemptedPassword)->send();

        //Make sure the JSON response contains a contentBody->text property with a value of "How are you Feeling?"
        $this->assertEquals("How are you feeling?", $response->body->data->contentBody->text);
        //Makue sure the JSON response contains an array of inputs as the contentBody->inputs property
        $this->assertTrue(is_array($response->body->data->contentBody->inputs));
        //Make sure the JSON response contains a contentHead->title property with a value of "How are you Feeling?"
        $this->assertEquals("How are you Feeling?", $response->body->data->contentHead->title);
        //Make sure the JSON response contains a user property with a value of "User Two" (the user that will be logged in)
        $this->assertEquals("User Two", $response->body->data->userName);
    }
}