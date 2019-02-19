<?php

namespace AppBundle\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints\Date;
use AppBundle\Entity\Respondent;
use AppBundle\Entity\Relationship;
use AppBundle\Entity\Wellness;
use AppBundle\Entity\WellnessProfessional;
use AppBundle\Entity\Group;
use AppBundle\Entity\GroupMember;

class UserStatWPFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        if(empty($manager->getRepository(User::class)->findOneBy(array('userID' => '2FAC2763-9FC0-FC21-4762-42330CEUSWP7'))))
        {
            //Create two users for the functional tests

            $user1 = new User();
            $user1->setUserId('2FAC2763-9FC0-FC21-4762-42330CEUSWP7');
            $user1->setFirstName('User');
            $user1->setLastName('One');
            $user1->setEmail('userstatwpcontroller@userone.com');
            $user1->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user1->setDate(new \DateTime('1969-11-28'));
            $user1->setCountry('CA');
            $user1->setCity('Saskatoon');
            $user1->setStatus('active');
            $user1->setIsActive(true);
            $manager->persist($user1);

            $user2 = new User();
            $user2->setUserId('2FAC2763-9FC0-FC21-4762-42330CEUSWP6');
            $user2->setFirstName('User');
            $user2->setLastName('Two');
            $user2->setEmail('userstatwpcontroller@usertwo.com');
            $user2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user2->setDate(new \DateTime('1969-11-28'));
            $user2->setCountry('CA');
            $user2->setCity('Saskatoon');
            $user2->setStatus('active');
            $user2->setIsActive(true);
            $manager->persist($user2);

            $user3 = new User();
            $user3->setUserId('2FAC2763-9FC0-FC21-4762-42330CEUSWP5');
            $user3->setFirstName('User');
            $user3->setLastName('Three');
            $user3->setEmail('userstatwpcontroller@userthree.com');
            $user3->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user3->setDate(new \DateTime('1969-11-28'));
            $user3->setCountry('CA');
            $user3->setCity('Saskatoon');
            $user3->setStatus('active');
            $user3->setIsActive(true);
            $manager->persist($user3);

            /* Updates for wellness pro relationship */
            //Add caregiver relationship
            $relationship = new Relationship();
            $relationship->setUserIdOne($user1);
            $relationship->setUserIdTwo($user2);
            $relationship->setType("support");
            $relationship->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $relationship->setStatus("active");
            $relationship->setRelationshipId();
            $manager->persist($relationship);

            //Make user1 a wellness pro
            $wp1 = new WellnessProfessional();
            $wp1->setUser($user1);
            $wp1->setPracticeName("Bob's Therapy Emporium");
            $wp1->setContactNumber('3061234567');
            $wp1->setContactEmail("bob@therapyemporium.ca");
            $manager->persist($wp1);

            /*    */

            //Add wellness pro relationship
            $relationship2 = new Relationship();
            $relationship2->setUserIdOne($user2);
            $relationship2->setUserIdTwo($user1);
            $relationship2->setType("wellness professional");
            $relationship2->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $relationship2->setStatus("active");
            $relationship2->setRelationshipId();
            $manager->persist($relationship2);

            //Add respondents
            $respondent = new Respondent();
            $respondent->setUser($user2);
            $respondent->setAtRisk(true);
            $respondent->setMentalState("Hungry");
            $respondent->setStressLevel(63650);
            $respondent->setEmergencyContact("IHateCats");
            $respondent->setDoctor("Caesar Mulan");
            $manager->persist($respondent);

            $respondent2 = new Respondent();
            $respondent2->setUser($user3);
            $respondent2->setAtRisk(true);
            $respondent2->setMentalState("Dead");
            $respondent2->setStressLevel(0);
            $respondent2->setEmergencyContact("Satan");
            $respondent2->setDoctor("Dr. Phil");
            $manager->persist($respondent2);

            //Add wellness records
            $wellnessRec1 = new Wellness();
            $wellnessRec1->setRespondent($respondent);
            $wellnessRec1->setMood(2);
            $wellnessRec1->setEnergy(2);
            $wellnessRec1->setThoughts(2);
            $wellnessRec1->setSleep(2);
            $wellnessRec1->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec1);

            $wellnessRec2 = new Wellness();
            $wellnessRec2->setRespondent($respondent);
            $wellnessRec2->setMood(4);
            $wellnessRec2->setEnergy(4);
            $wellnessRec2->setThoughts(4);
            $wellnessRec2->setSleep(4);
            $wellnessRec2->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec2);

            #region Org

            $user1 = new User();
            $user1->setUserId('3FAC2763-9FC0-FC21-4762-42330CEUSWP7');
            $user1->setFirstName('User');
            $user1->setLastName('One');
            $user1->setEmail('userstatwpcontroller@userfour.com');
            $user1->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user1->setDate(new \DateTime('1969-11-28'));
            $user1->setCountry('CA');
            $user1->setCity('Saskatoon');
            $user1->setStatus('active');
            $user1->setIsActive(true);
            $manager->persist($user1);

            //Org member
            $user2 = new User();
            $user2->setUserId('3FAC2763-9FC0-FC21-4762-42330CEUSWP6');
            $user2->setFirstName('User');
            $user2->setLastName('Two');
            $user2->setEmail('userstatwpcontroller@userfive.com');
            $user2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user2->setDate(new \DateTime('1969-11-28'));
            $user2->setCountry('CA');
            $user2->setCity('Saskatoon');
            $user2->setStatus('active');
            $user2->setIsActive(true);
            $manager->persist($user2);

            //Non-member
            $user3 = new User();
            $user3->setUserId('3FAC2763-9FC0-FC21-4762-42330CEUSWP5');
            $user3->setFirstName('User');
            $user3->setLastName('Three');
            $user3->setEmail('userstatwpcontroller@usersix.com');
            $user3->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user3->setDate(new \DateTime('1969-11-28'));
            $user3->setCountry('CA');
            $user3->setCity('Saskatoon');
            $user3->setStatus('active');
            $user3->setIsActive(true);
            $manager->persist($user3);

            /*Create an organization and set user1 as admin and user2 as member of it*/
            $org = new Group();
            $org->setgroupId('3AABE519-CEB2-4962-BCE2-397BA83USWP1');
            $org->setGroupName("Faceless Corporation Inc.");
            $org->setGroupDesc("All men must die.");
            $org->setGroupType("organization");
            $manager->persist($org);

            //Admin member
            $orgMemAdmin = new GroupMember();
            $orgMemAdmin->setUser($user1);
            $orgMemAdmin->setStatus("active");
            $orgMemAdmin->setDateJoined(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $orgMemAdmin->setGroupRole("admin");
            $orgMemAdmin->setGroup($org);
            $orgMemAdmin->setGroupMemberId();
            $manager->persist($orgMemAdmin);

            //Standard member
            $orgMem = new GroupMember();
            $orgMem->setUser($user2);
            $orgMem->setStatus("active");
            $orgMem->setDateJoined(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $orgMem->setGroupRole("standard");
            $orgMem->setGroup($org);
            $orgMem->setGroupMemberId();
            $manager->persist($orgMem);

            //Add respondents (need to be here cause they are the only ones with wellness records)
            $respondent = new Respondent();
            $respondent->setUser($user2);
            $respondent->setAtRisk(true);
            $respondent->setMentalState("Hungry");
            $respondent->setStressLevel(63650);
            $respondent->setEmergencyContact("IHateCats");
            $respondent->setDoctor("Caesar Mulan");
            $manager->persist($respondent);

            $respondent2 = new Respondent();
            $respondent2->setUser($user3);
            $respondent2->setAtRisk(true);
            $respondent2->setMentalState("Dead");
            $respondent2->setStressLevel(0);
            $respondent2->setEmergencyContact("Satan");
            $respondent2->setDoctor("Dr. Phil");
            $manager->persist($respondent2);

            //Add wellness records
            $wellnessRec1 = new Wellness();
            $wellnessRec1->setRespondent($respondent);
            $wellnessRec1->setMood(2);
            $wellnessRec1->setEnergy(2);
            $wellnessRec1->setThoughts(2);
            $wellnessRec1->setSleep(2);
            $wellnessRec1->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec1);

            $wellnessRec2 = new Wellness();
            $wellnessRec2->setRespondent($respondent);
            $wellnessRec2->setMood(4);
            $wellnessRec2->setEnergy(4);
            $wellnessRec2->setThoughts(4);
            $wellnessRec2->setSleep(4);
            $wellnessRec2->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec2);

            #endregion

            $manager->flush();
        }

    }


    public function unload(ObjectManager $manager)
    {
        //Remove wellness records
        $wellnessRecs = $manager->getRepository(Wellness::class)
            ->findBy(array('date'=>date("Y-m-d")));

        foreach ($wellnessRecs as $rec)
        {
        	if(!empty($rec))
            {
                $manager->remove($rec);
            }
        }

        //Remove wellness relationship
        $relationship = $manager->getRepository(Relationship::class)
            ->findOneBy(array('relationshipId' => '2FAC2763-9FC0-FC21-4762-42330CEUSWP6:2FAC2763-9FC0-FC21-4762-42330CEUSWP7:wellnessprofessional'));
        if(!empty($relationship))
        {
            $manager->remove($relationship);
        }

        //Remove relationship
        $relationship = $manager->getRepository(Relationship::class)
            ->findOneBy(array('relationshipId' => '2FAC2763-9FC0-FC21-4762-42330CEUSWP7:2FAC2763-9FC0-FC21-4762-42330CEUSWP6:support'));

        if(!empty($relationship))
        {
            $manager->remove($relationship);
        }

        //Remove Respondent for user 2
        $respondentOne = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '2FAC2763-9FC0-FC21-4762-42330CEUSWP6'));

        if (!empty($respondentOne))
        {
        	$manager->remove($respondentOne);
        }


        //Remove Respondent for user 3
        $respondentTwo = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '2FAC2763-9FC0-FC21-4762-42330CEUSWP5'));

        if (!empty($respondentTwo))
        {
        	$manager->remove($respondentTwo);
        }

        //Remove wellness professional
        $wp = $manager->getRepository(WellnessProfessional::class)
            ->findOneBy(array('user'=>'2FAC2763-9FC0-FC21-4762-42330CEUSWP7'));

        if(!empty($wp))
        {
            $manager->remove($wp);
        }

        //Remove user1
        $user1 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '2FAC2763-9FC0-FC21-4762-42330CEUSWP7'));

        if(!empty($user1))
        {
            $manager->remove($user1);
        }

        //Remove user2
        $user2 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '2FAC2763-9FC0-FC21-4762-42330CEUSWP6'));

        if(!empty($user2))
        {
            $manager->remove($user2);
        }

        //Remove user3
        $user3 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '2FAC2763-9FC0-FC21-4762-42330CEUSWP5'));

        if(!empty($user3))
        {
            $manager->remove($user3);
        }

        #region Org

        //Remove org members
        $orgMem1 = $manager->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'3AABE519-CEB2-4962-BCE2-397BA83USWP1:3FAC2763-9FC0-FC21-4762-42330CEUSWP7'));

        if(!empty($orgMem1))
        {
            $manager->remove($orgMem1);
        }

        $orgMem2 = $manager->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'3AABE519-CEB2-4962-BCE2-397BA83USWP1:3FAC2763-9FC0-FC21-4762-42330CEUSWP6'));

        if(!empty($orgMem2))
        {
            $manager->remove($orgMem2);
        }

        //Remove group
        $org = $manager->getRepository(Group::class)
            ->findOneBy(array('groupID'=>'3AABE519-CEB2-4962-BCE2-397BA83USWP1'));

        if(!empty($org))
        {
            $manager->remove($org);
        }

        //Remove Respondent for user 2
        $respondentOne = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '3FAC2763-9FC0-FC21-4762-42330CEUSWP6'));

        if (!empty($respondentOne))
        {
        	$manager->remove($respondentOne);
        }


        //Remove Respondent for user 3
        $respondentTwo = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '3FAC2763-9FC0-FC21-4762-42330CEUSWP5'));

        if (!empty($respondentTwo))
        {
        	$manager->remove($respondentTwo);
        }

        //Remove user1
        $user1 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '3FAC2763-9FC0-FC21-4762-42330CEUSWP7'));

        if(!empty($user1))
        {
            $manager->remove($user1);
        }

        //Remove user2
        $user2 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '3FAC2763-9FC0-FC21-4762-42330CEUSWP6'));

        if(!empty($user2))
        {
            $manager->remove($user2);
        }

        //Remove user3
        $user3 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '3FAC2763-9FC0-FC21-4762-42330CEUSWP5'));

        if(!empty($user3))
        {
            $manager->remove($user3);
        }

        #endregion

        $manager->flush();
    }
}