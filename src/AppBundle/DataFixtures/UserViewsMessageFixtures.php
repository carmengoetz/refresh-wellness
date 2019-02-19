<?php
namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Relationship;
use AppBundle\Entity\Message;
use AppBundle\Entity\Conversation;

/**
 * Fixtures to test a user sending a message.
 *
 * @version 1.0
 * @author cst231
 */
class UserViewsMessageFixtures extends Fixture
{
    /**
     * Load the fixtures we want to use.
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        //Only load fixtures if a user with this id does not already exist
        if(empty($manager->getRepository(User::class)->findOneBy(array('userID' => 'VIEWSMSG-8475-491E-A63D-F9F356F98C81'))))
        {
            /* Make 3 users */
            //The user who will be viewing the messages/conversations
            $bucky = new User();
            $bucky->setUserId('VIEWSMSG-8475-491E-A63D-F9F356F98C81');
            $bucky->setFirstName('Bucky');
            $bucky->setLastName('Barnes');
            $bucky->setEmail('vmBucky@gmail.com');
            $bucky->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $bucky->setDate(new \DateTime('1969-11-28'));
            $bucky->setCountry('CA');
            $bucky->setCity('Saskatoon');
            $bucky->setStatus('active');
            $bucky->setIsActive(true);
            $manager->persist($bucky);

            //The user who will have a conversation with the receiving user with only 2 messages
            $tony = new User();
            $tony->setUserId('VIEWSMSG-8475-491E-A63D-F9F356F98C82');
            $tony->setFirstName('Tony');
            $tony->setLastName('Stark');
            $tony->setEmail('vmTony@gmail.com');
            $tony->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $tony->setDate(new \DateTime('1969-11-28'));
            $tony->setCountry('CA');
            $tony->setCity('Saskatoon');
            $tony->setStatus('active');
            $tony->setIsActive(true);
            $manager->persist($tony);

            //The user who will have a conversation with the receiving user with more than 20 messages
            $steve = new User();
            $steve->setUserId('VIEWSMSG-8475-491E-A63D-F9F356F98C83');
            $steve->setFirstName('Steve');
            $steve->setLastName('Rogers');
            $steve->setEmail('vmSteve@gmail.com');
            $steve->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $steve->setDate(new \DateTime('1969-11-28'));
            $steve->setCountry('CA');
            $steve->setCity('Saskatoon');
            $steve->setStatus('active');
            $steve->setIsActive(true);
            $manager->persist($steve);

            //creating 44 users to have conversations with bucky
            for ($i = 1; $i <= 44; $i++)
            {
                //creating a user
                $user = new User();
                $user->setUserId('VIEWSMSG-8475-491E-A63D-F9F356F98C8' . ($i + 3));
                $user->setFirstName('User');
                $user->setLastName($i);
                $user->setEmail("vmUser$i@gmail.com");
                $user->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
                $user->setDate(new \DateTime('1969-11-28'));
                $user->setCountry('CA');
                $user->setCity('Saskatoon');
                $user->setStatus('active');
                $user->setIsActive(true);
                $manager->persist($user);

                //creating a relationship between bucky and the user
                $relationship = new Relationship();
                $relationship->setUserIdOne($bucky);
                $relationship->setUserIdTwo($user);
                $relationship->setType("friend");
                $relationship->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
                $relationship->setStatus("active");
                $relationship->setRelationshipId();
                $manager->persist($relationship);

                //Create message and that is sent to Bucky
                //getting a random date to set for the message
                //between 1/1/2018 and 3/14/2018
                $int = rand(1514782800, 1519880400);
                $date = new \DateTime(date("Y-m-d H:i:s", $int));

            	$message = new Message();
                $message->setSender($user);
                $message->setReceiver($bucky);
                $message->setMessageContent("Hi from User $i");
                $message->setDateSent($date);
                $message->setTimeSent($date);
                $manager->persist($message);

                //creating a conversation between bucky and the user
                $conversation = new Conversation();
                $conversation->setUserOneID($bucky);
                $conversation->setUserTwoID($user);
                $conversation->setLastMessage($message);
                $manager->persist($conversation);

            }

            //add relationship between receiver user and other two users

            //Bucky to Steve
            $relationship = new Relationship();
            $relationship->setUserIdOne($bucky);
            $relationship->setUserIdTwo($steve);
            $relationship->setType("friend");
            $relationship->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $relationship->setStatus("active");
            $relationship->setRelationshipId();
            $manager->persist($relationship);

            //Set 25 messages from Steve to bucky
            $message = null;

            for ($i = 0; $i < 22; $i++)
            {
                //getting a random date to set for the message
                //between 1/1/2018 and 3/14/2018
                $int = rand(1514782800, 1519880400);
                $date = new \DateTime(date("Y-m-d H:i:s", $int));

            	$message = new Message();
                $message->setSender($steve);
                $message->setReceiver($bucky);
                $message->setMessageContent("Hello?");
                $message->setDateSent($date);
                $message->setTimeSent($date);
                $manager->persist($message);

                //getting a random date to set for the message
                //between 1/1/2018 and 3/14/2018
                $int = rand(1514782800, 1519880400);
                $date = new \DateTime(date("Y-m-d H:i:s", $int));

                $message = new Message();
                $message->setSender($bucky);
                $message->setReceiver($steve);
                $message->setMessageContent("Hi?");
                $message->setDateSent($date);
                $message->setTimeSent($date);
                $manager->persist($message);
            }

            //Set the last message from Steve to Bucky in the conversation table
            $conversationSteve = new Conversation();
            $conversationSteve->setUserOneID($bucky);
            $conversationSteve->setUserTwoID($steve);
            $conversationSteve->setLastMessage($message);
            $manager->persist($conversationSteve);

            //Commit to the database
            $manager->flush();
        }
    }

    /**
     * Remove all records and changes to the database.
     * @param ObjectManager $manager
     */
    public function unload(ObjectManager $manager)
    {
        //Remove all conversations
        $conversations = $manager->getRepository(Conversation::class)->findAll();
        foreach ($conversations as $conversation)
        {
        	$manager->remove($conversation);
        }

        //Remove all messages
        $messages = $manager->getRepository(Message::class)->findAll();
        foreach ($messages as $message)
        {
        	$manager->remove($message);
        }

        //remove all relationships
        $relationships = $manager->getRepository(Relationship::class)->findAll();
        foreach ($relationships as $relationship)
        {
        	$manager->remove($relationship);
        }

        //remove all users
        $users = $manager->getRepository(User::class)->findAll();
        foreach ($users as $user)
        {
        	$manager->remove($user);
        }

        $manager->flush();
    }

}