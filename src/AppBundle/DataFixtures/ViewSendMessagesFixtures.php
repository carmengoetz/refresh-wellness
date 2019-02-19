<?php
namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Relationship;
use AppBundle\Entity\WellnessProfessional;
use AppBundle\Entity\Message;
use AppBundle\Entity\Conversation;

/**
 * Fixtures to test a user sending a message.
 *
 * @version 1.0
 * @author cst236
 */
class ViewSendMessagesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Only load fixtures if a user with this id does not already exist
        if(empty($manager->getRepository(User::class)->findOneBy(array('userID' => 'fred-flintstone'))))
        {
            /* Make 3 users */

            //This user will have one message
            $user = new User();
            $user->setUserId('fred-flintstone');
            $user->setFirstName('Fred');
            $user->setLastName('Flintstone');
            $user->setEmail('messFred@email.com');
            $user->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user->setDate(new \DateTime('1969-11-28'));
            $user->setCountry('CA');
            $user->setCity('Saskatoon');
            $user->setStatus('active');
            $user->setIsActive(true);
            $manager->persist($user);

            //This user sends the message to the first user
            $user2 = new User();
            $user2->setUserId('wilma-flintstone');
            $user2->setFirstName('Wilma');
            $user2->setLastName('Flintstone');
            $user2->setEmail('messWilma@gmail.com');
            $user2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user2->setDate(new \DateTime('1969-11-28'));
            $user2->setCountry('CA');
            $user2->setCity('Saskatoon');
            $user2->setStatus('active');
            $user2->setIsActive(true);
            $manager->persist($user2);

            //This user will have 250 messages
            $user3 = new User();
            $user3->setUserId('barney-rubble');
            $user3->setFirstName('Barney');
            $user3->setLastName('Rubble');
            $user3->setEmail('messBarney@gmail.com');
            $user3->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user3->setDate(new \DateTime('1969-11-28'));
            $user3->setCountry('CA');
            $user3->setCity('Saskatoon');
            $user3->setStatus('active');
            $user3->setIsActive(true);
            $manager->persist($user3);

            //User to have 50 many conversations
            $user4 = new User();
            $user4->setUserId('betty-rubble');
            $user4->setFirstName('Betty');
            $user4->setLastName('Rubble');
            $user4->setEmail('messBetty@gmail.com');
            $user4->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user4->setDate(new \DateTime('1969-11-28'));
            $user4->setCountry('CA');
            $user4->setCity('Saskatoon');
            $user4->setStatus('active');
            $user4->setIsActive(true);
            $manager->persist($user4);

            //This user has no messages
            $user5 = new User();
            $user5->setUserId('pebbles-flintstone');
            $user5->setFirstName('Pebbles');
            $user5->setLastName('Flintstone');
            $user5->setEmail('messPebbles@gmail.com');
            $user5->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user5->setDate(new \DateTime('1969-11-28'));
            $user5->setCountry('CA');
            $user5->setCity('Saskatoon');
            $user5->setStatus('active');
            $user5->setIsActive(true);
            $manager->persist($user5);

            //This user has no messages
            $user6 = new User();
            $user6->setUserId('bambam-rubble');
            $user6->setFirstName('Bam Bam');
            $user6->setLastName('Rubble');
            $user6->setEmail('messBamBam@gmail.com');
            $user6->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user6->setDate(new \DateTime('1969-11-28'));
            $user6->setCountry('CA');
            $user6->setCity('Saskatoon');
            $user6->setStatus('active');
            $user6->setIsActive(true);
            $manager->persist($user6);

            //add relationship between users
            $rel = new Relationship();
            $rel->setUserIdOne($user);
            $rel->setUserIdTwo($user2);
            $rel->setType("support");
            $rel->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $rel->setStatus("active");
            $rel->setRelationshipId();
            $manager->persist($rel);

            $rel2 = new Relationship();
            $rel2->setUserIdOne($user);
            $rel2->setUserIdTwo($user3);
            $rel2->setType("friend");
            $rel2->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $rel2->setStatus("active");
            $rel2->setRelationshipId();
            $manager->persist($rel2);

            $rel3 = new Relationship();
            $rel3->setUserIdOne($user2);
            $rel3->setUserIdTwo($user3);
            $rel3->setType("client");
            $rel3->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $rel3->setStatus("active");
            $rel3->setRelationshipId();
            $manager->persist($rel3);

            $rel4 = new Relationship();
            $rel4->setUserIdOne($user);
            $rel4->setUserIdTwo($user4);
            $rel4->setType("client");
            $rel4->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $rel4->setStatus("active");
            $rel4->setRelationshipId();
            $manager->persist($rel4);

            $rel5 = new Relationship();
            $rel5->setUserIdOne($user);
            $rel5->setUserIdTwo($user5);
            $rel5->setType("client");
            $rel5->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $rel5->setStatus("active");
            $rel5->setRelationshipId();
            $manager->persist($rel5);


            //create messages

            //1 message for user1
            $msgUser1 = new Message();
            $msgUser1->setSender($user);
            $msgUser1->setReceiver($user4);
            $msgUser1->setDateSent(new \DateTime('2015-05-01'));
            $msgUser1->setTimeSent(new \DateTime("4:00 pm"));
            $msgUser1->setMessageContent("Heya Betty");
            $msgUser1->setIsRead(true);
            $manager->persist($msgUser1);

            $msg1Convo = new Conversation();
            $msg1Convo->setUserOneID($user);
            $msg1Convo->setUserTwoID($user4);
            $msg1Convo->setLastMessage($msgUser1);
            $manager->persist($msg1Convo);

            $msgUser2 = new Message();
            $msgUser2->setSender($user);
            $msgUser2->setReceiver($user5);
            $msgUser2->setDateSent(new \DateTime('2015-05-01'));
            $msgUser2->setTimeSent(new \DateTime("4:00 pm"));
            $msgUser2->setMessageContent("How's my little Pebbles?");
            $msgUser2->setIsRead(true);
            $manager->persist($msgUser2);

            $msg2Convo = new Conversation();
            $msg2Convo->setUserOneID($user);
            $msg2Convo->setUserTwoID($user5);
            $msg2Convo->setLastMessage($msgUser2);
            $manager->persist($msg2Convo);




            $manager->flush();
        }
    }


}