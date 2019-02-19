<?php

namespace AppBundle\DataFixtures;

/**
 * ViewAggregateStatsFixtures short summary.
 *
 * ViewAggregateStatsFixtures description.
 *
 * @version 1.0
 * @author CST236
 */

use AppBundle\Entity\User;
use AppBundle\Entity\Respondent;
use AppBundle\Entity\Wellness;
use DateTime;
use AppBundle\Entity\GroupMember;
use AppBundle\Entity\Group;
use AppBundle\Entity\WellnessProfessional;
use AppBundle\Entity\Relationship;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class ViewAggregateStatsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        if (empty($manager->getRepository(User::class)->findOneBy(array('userID' => 'AggregateUserStatAdmin'))))
        {
            $user =new User();
            $user->setUserId("AggregateUserStatAdmin");
            $user->setFirstName("Org");
            $user->setLastName("admin");
            $user->setEmail("emailOrgAdmin@email.com");
            $user->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user->setDate(new \DateTime('1969-11-28'));
            $user->setCountry('CA');
            $user->setCity('Saskatoon');
            $user->setStatus('active');
            $user->setIsActive(true);
            $manager->persist($user);

            $user10 =new User();
            $user10->setUserId("AggregateUserStatAdminNoMembers");
            $user10->setFirstName("Org");
            $user10->setLastName("admin");
            $user10->setEmail("emailOrgAdminNoMembers@email.com");
            $user10->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user10->setDate(new \DateTime('1969-11-28'));
            $user10->setCountry('CA');
            $user10->setCity('Saskatoon');
            $user10->setStatus('active');
            $user10->setIsActive(true);
            $manager->persist($user10);

            $user1 =new User();
            $user1->setUserId("AggregateUserStatWellProf");
            $user1->setFirstName("Wellness");
            $user1->setLastName("Prof");
            $user1->setEmail("emailWellProf@email.com");
            $user1->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user1->setDate(new \DateTime('1969-11-28'));
            $user1->setCountry('CA');
            $user1->setCity('Winnipeg');
            $user1->setStatus('active');
            $user1->setIsActive(true);
            $manager->persist($user1);

            $user2 =new User();
            $user2->setUserId("AggregateUserStatWellProfNoPatients");
            $user2->setFirstName("Wellness");
            $user2->setLastName("Prof with no patients");
            $user2->setEmail("emailWellProfNoPatients@email.com");
            $user2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user2->setDate(new \DateTime('1969-11-28'));
            $user2->setCountry('CA');
            $user2->setCity('Saskatoon');
            $user2->setStatus('active');
            $user2->setIsActive(true);
            $manager->persist($user2);

            /*Create an organization and set user1 as admin and user2 as member of it*/
            $org = new Group();
            $org->setgroupId('OrgForAggregateStats');
            $org->setGroupName("Faceless Corporation Inc.");
            $org->setGroupDesc("All men must die.");
            $org->setGroupType("organization");
            $manager->persist($org);

            //Admin member
            $orgMemAdmin = new GroupMember();
            $orgMemAdmin->setUser($user);
            $orgMemAdmin->setStatus("active");
            $orgMemAdmin->setDateJoined(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $orgMemAdmin->setGroupRole("admin");
            $orgMemAdmin->setGroup($org);
            $orgMemAdmin->setGroupMemberId();
            $manager->persist($orgMemAdmin);

            //This group has no members
            $org1 = new Group();
            $org1->setgroupId('OrgForAggregateStatsNoMembers');
            $org1->setGroupName("Faceless Corporation Inc.");
            $org1->setGroupDesc("All men must die.");
            $org1->setGroupType("organization");
            $manager->persist($org1);

            //Admin member for no respondent group
            $orgMemAdmin = new GroupMember();
            $orgMemAdmin->setUser($user10);
            $orgMemAdmin->setStatus("active");
            $orgMemAdmin->setDateJoined(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $orgMemAdmin->setGroupRole("admin");
            $orgMemAdmin->setGroup($org1);
            $orgMemAdmin->setGroupMemberId();
            $manager->persist($orgMemAdmin);

            //Make user1 a wellness pro
            $wp1 = new WellnessProfessional();
            $wp1->setUser($user1);
            $wp1->setPracticeName("Bob's Therapy Emporium");
            $wp1->setContactNumber('3061234567');
            $wp1->setContactEmail("bob@therapyemporium.ca");
            $manager->persist($wp1);

            //Make user2 a wellness pro with no patients
            $wp2 = new WellnessProfessional();
            $wp2->setUser($user2);
            $wp2->setPracticeName("Bob's Therapy Emporium");
            $wp2->setContactNumber('3061234567');
            $wp2->setContactEmail("bob@therapyemporium.ca");
            $manager->persist($wp2);



            for($i = 0; $i < 100; $i++)
            {
                $user =new User();
                $user->setUserId("AggregateUserStat" . $i);
                $user->setFirstName($i);
                $user->setLastName($i);
                $user->setEmail("email".$i."@email.com");
                $user->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
                $user->setDate(new \DateTime('1969-11-28'));
                $user->setCountry('CA');
                $user->setCity('Saskatoon');
                $user->setStatus('active');
                $user->setIsActive(true);
                $manager->persist($user);

                $respondent = new Respondent();
                $respondent->setUser($user);
                $respondent->setAtRisk(true);
                $respondent->setMentalState("Hungry");
                $respondent->setStressLevel(63650);
                $respondent->setEmergencyContact("IHateCats");
                $respondent->setDoctor("Caesar Mulan");
                $manager->persist($respondent);

                $wellnessRec1 = new Wellness();
                $wellnessRec1->setRespondent($respondent);
                $wellnessRec1->setMood(2);
                $wellnessRec1->setEnergy(2);
                $wellnessRec1->setThoughts(2);
                $wellnessRec1->setSleep(2);
                $wellnessRec1->setDate(date("Y-m-d"));
                $manager->persist($wellnessRec1);

                for($j = 0; $j < 8; $j++)
                {
                    $wellnessRec1 = new Wellness();
                    $wellnessRec1->setRespondent($respondent);
                    $wellnessRec1->setMood(rand(0,10));
                    $wellnessRec1->setEnergy(rand(0,10));
                    $wellnessRec1->setThoughts(rand(0,10));
                    $wellnessRec1->setSleep(rand(0,10));
                    $wellnessRec1->setDate("Jan 0" . ($j + 1) . ", 2018");
                    $manager->persist($wellnessRec1);
                }
                if($i < 90)
                {
                    $orgMem = new GroupMember();
                    $orgMem->setUser($user);
                    $orgMem->setStatus("active");
                    $orgMem->setDateJoined(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
                    $orgMem->setGroupRole("member");
                    $orgMem->setGroup($org);
                    $orgMem->setGroupMemberId();
                    $manager->persist($orgMem);
                }
                else
                {
                    $relationship2 = new Relationship();
                    $relationship2->setUserIdOne($user);
                    $relationship2->setUserIdTwo($user1);
                    $relationship2->setType("wellness professional");
                    $relationship2->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
                    $relationship2->setStatus("active");
                    $relationship2->setRelationshipId();
                    $manager->persist($relationship2);
                }

            }

            $manager->flush();

            for ($i = 0; $i < 15; $i++)
            {
                $user3 = new User();
                $user3->setUserId("AggregateUserStatWellProfNoPatients" . $i);
                $user3->setFirstName("Wellness");
                $user3->setLastName("Prof with no patients");
                $user3->setEmail("emailwp" .$i ."@email.com");
                $user3->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
                $user3->setDate(new \DateTime('1969-11-28'));
                $user3->setCountry('CA');
                $user3->setCity('Saskatoon');
                $user3->setStatus('active');
                $user3->setIsActive(true);
                $manager->persist($user3);

                $wp3 = new WellnessProfessional();
                $wp3->setUser($user3);
                $wp3->setPracticeName("Bob's cool place " . $i);
                $wp3->setContactNumber('3061234567');
                $wp3->setContactEmail("bob@therapyemporium.ca");
                $manager->persist($wp3);
            }

            $manager->flush();
        }
    }

    public function unload(ObjectManager $manager)
    {
        $wellnessRecs = $manager->getRepository(Wellness::class)
            ->findAll();

        foreach ($wellnessRecs as $rec)
        {
        	if(!empty($rec))
            {
                $manager->remove($rec);
            }
        }

        for($i = 0; $i < 100; $i++)
        {
            if($i < 90)
            {
                $orgMem1 = $manager->getRepository(GroupMember::class)
                    ->findOneBy(array('groupMemberId'=>'OrgForAggregateStats:AggregateUserStat' . $i));

                if(!empty($orgMem1))
                {
                    $manager->remove($orgMem1);
                }
            }
            else
            {
                $relationship = $manager->getRepository(Relationship::class)
                    ->findOneBy(array('relationshipId' => 'AggregateUserStat' . $i . ':AggregateUserStatWellProf:wellnessprofessional'));

                if(!empty($relationship))
                {
                    $manager->remove($relationship);
                }
            }

            $respondentOne = $manager->getRepository(Respondent::class)
                ->findOneBy(array('user' => 'AggregateUserStat' . $i));

            if (!empty($respondentOne))
            {
                $manager->remove($respondentOne);
            }


            $user1 = $manager->getRepository(User::class)
                ->findOneBy(array('userID' => 'AggregateUserStat' . $i ));

            if(!empty($user1))
            {
                $manager->remove($user1);
            }
        }

        //Remove org members
        $userOrgAd = $manager->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'OrgForAggregateStats:AggregateUserStatAdmin'));

        if(!empty($userOrgAd))
        {
            $manager->remove($userOrgAd);
        }

        $userOrgAd1 = $manager->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'OrgForAggregateStatsNoMembers:AggregateUserStatAdminNoMembers'));

        if(!empty($userOrgAd1))
        {
            $manager->remove($userOrgAd1);
        }

        $orgMem2 = $manager->getRepository(User::class)
           ->findOneBy(array('userID'=>'AggregateUserStatAdminNoMembers'));

        if(!empty($orgMem2))
        {
            $manager->remove($orgMem2);
        }

        $orgMem1 = $manager->getRepository(User::class)
            ->findOneBy(array('userID'=>'AggregateUserStatAdmin'));

        if(!empty($orgMem1))
        {
            $manager->remove($orgMem1);
        }

        //NEED TO REMOVE GROUP
        $wp1 = $manager->getRepository(WellnessProfessional::class)
            ->findOneBy(array('user'=>'AggregateUserStatWellProfNoPatients'));

        if(!empty($wp1))
        {
            $manager->remove($wp1);
        }

        $userWP1 = $manager->getRepository(User::class)
            ->findOneBy(array('userID'=>'AggregateUserStatWellProfNoPatients'));

        if(!empty($userWP1))
        {
            $manager->remove($userWP1);
        }

        $org1 = $manager->getRepository(Group::class)
            ->findOneBy(array('groupID'=>'OrgForAggregateStatsNoMembers'));

        if(!empty($org1))
        {
            $manager->remove($org1);
        }



        //Remove wellness professional
        $wp = $manager->getRepository(WellnessProfessional::class)
            ->findOneBy(array('user'=>'AggregateUserStatWellProf'));

        if(!empty($wp))
        {
            $manager->remove($wp);
        }

        $userWP = $manager->getRepository(User::class)
            ->findOneBy(array('userID'=>'AggregateUserStatWellProf'));

        if(!empty($userWP))
        {
            $manager->remove($userWP);
        }

        //Remove group
        $org = $manager->getRepository(Group::class)
            ->findOneBy(array('groupID'=>'OrgForAggregateStats'));

        if(!empty($org))
        {
            $manager->remove($org);
        }

        $manager->flush();
    }
}