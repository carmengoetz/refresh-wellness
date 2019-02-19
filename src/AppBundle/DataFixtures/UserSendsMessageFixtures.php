<?php
namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Relationship;
use AppBundle\Entity\WellnessProfessional;
/**
 * Fixtures to test a user sending a message.
 *
 * @version 1.0
 * @author cst231
 */
class UserSendsMessageFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Only load fixtures if a user with this id does not already exist
        if(empty($manager->getRepository(User::class)->findOneBy(array('userID' => '4950F3C6-8475-491E-A63D-F9F356F98C81'))))
        {
            /* Make 3 users */
            //The user who will be sending the message
            $sender = new User();
            $sender->setUserId('4950F3C6-8475-491E-A63D-F9F356F98C81');
            $sender->setFirstName('Tim');
            $sender->setLastName('Burton');
            $sender->setEmail('messageSenderOne@gmail.com');
            $sender->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $sender->setDate(new \DateTime('1969-11-28'));
            $sender->setCountry('CA');
            $sender->setCity('Saskatoon');
            $sender->setStatus('active');
            $sender->setIsActive(true);
            $manager->persist($sender);

            //The user who will successfully receive a message
            $receiver = new User();
            $receiver->setUserId('4950F3C6-8475-491E-A63D-F9F356F98C82');
            $receiver->setFirstName('Johnny');
            $receiver->setLastName('Depp');
            $receiver->setEmail('messageReceiver@gmail.com');
            $receiver->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $receiver->setDate(new \DateTime('1969-11-28'));
            $receiver->setCountry('CA');
            $receiver->setCity('Saskatoon');
            $receiver->setStatus('active');
            $receiver->setIsActive(true);
            $manager->persist($receiver);

            //The wellness pro who the sender cannot send to
            $notReceiver = new User();
            $notReceiver->setUserId('4950F3C6-8475-491E-A63D-F9F356F98C83');
            $notReceiver->setFirstName('Helena');
            $notReceiver->setLastName('Bonham-Carter');
            $notReceiver->setEmail('messageNotReceiver@gmail.com');
            $notReceiver->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $notReceiver->setDate(new \DateTime('1969-11-28'));
            $notReceiver->setCountry('CA');
            $notReceiver->setCity('Saskatoon');
            $notReceiver->setStatus('active');
            $receiver->setIsActive(true);
            $manager->persist($notReceiver);

            $wp = new WellnessProfessional();
            $wp->setUser($notReceiver);
            $wp->setPracticeName("Avada Kedavra's");
            $wp->setContactNumber('3061234567');
            $wp->setContactEmail("avadakedavra@hogwarts.wiz.uk.co");
            $manager->persist($wp);

            //add relationship between sender and receiver
            $relationship = new Relationship();
            $relationship->setUserIdOne($sender);
            $relationship->setUserIdTwo($receiver);
            $relationship->setType("friend");
            $relationship->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $relationship->setStatus("active");
            $relationship->setRelationshipId();
            $manager->persist($relationship);

            $manager->flush();
        }
    }

    public function unload(ObjectManager $manager)
    {
        //remove relationship
        $relationship = $manager->getRepository(Relationship::class)
            ->findOneBy((array('relationshipId'=>'4950F3C6-8475-491E-A63D-F9F356F98C81:4950F3C6-8475-491E-A63D-F9F356F98C82:friend')));
        if (!empty($relationship))
        {
        	$manager->remove($relationship);
        }

        //remove wellness professional
        $wp = $manager->getRepository(WellnessProfessional::class)
            ->findOneBy((array('user'=>'4950F3C6-8475-491E-A63D-F9F356F98C83')));
        if (!empty($wp))
        {
        	$manager->remove($wp);
        }

        //remove sender
        $sender = $manager->getRepository(User::class)
            ->findOneBy((array('userID'=>'4950F3C6-8475-491E-A63D-F9F356F98C81')));
        if (!empty($sender))
        {
        	$manager->remove($sender);
        }

        //remove receiver
        $receiver = $manager->getRepository(User::class)
            ->findOneBy((array('userID'=>'4950F3C6-8475-491E-A63D-F9F356F98C82')));
        if (!empty($receiver))
        {
        	$manager->remove($receiver);
        }

        //remove notReceiver
        $notReceiver = $manager->getRepository(User::class)
            ->findOneBy((array('userID'=>'4950F3C6-8475-491E-A63D-F9F356F98C83')));
        if (!empty($notReceiver))
        {
        	$manager->remove($notReceiver);
        }
        $manager->flush();
    }
}