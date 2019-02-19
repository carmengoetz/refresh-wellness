<?php

namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Respondent;
use AppBundle\Entity\Relationship;
use AppBundle\Entity\Group;
use AppBundle\Entity\GroupMember;
use AppBundle\Entity\Wellness;

/**
 * Fixtures for the org admin viewing their org members' stats in aggregate.
 *
 * @version 1.0
 * @author cst231, cst245
 */
class OrgAggStatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        #region OrgAdmin

        //Make user
        $orgAdminUser = new User();
        $orgAdminUser->setUserId('FC22816A-314C-4647-88F9-ECD5CA4F47F1');
        $orgAdminUser->setFirstName('User');
        $orgAdminUser->setLastName('One');
        $orgAdminUser->setEmail('orgAdminAggregate@gmail.com');
        $orgAdminUser->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
        $orgAdminUser->setDate(new \DateTime('1969-11-28'));
        $orgAdminUser->setCountry('CA');
        $orgAdminUser->setCity('Saskatoon');
        $orgAdminUser->setStatus('active');
        $orgAdminUser->setIsActive(true);
        $manager->persist($orgAdminUser);

        //Make org
        $org = new Group();
        $org->setgroupId('ORG2816A-314C-4647-88F9-ECD5CA4F47F1');
        $org->setGroupName("The Iron Bank");
        $org->setGroupDesc("Money Money Money");
        $org->setGroupType("organization");
        $manager->persist($org);

        //Make user org admin
        $orgMemAdmin = new GroupMember();
        $orgMemAdmin->setUser($orgAdminUser);
        $orgMemAdmin->setStatus("active");
        $orgMemAdmin->setDateJoined(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
        $orgMemAdmin->setGroupRole("admin");
        $orgMemAdmin->setGroup($org);
        $orgMemAdmin->setGroupMemberId();
        $manager->persist($orgMemAdmin);

        #endregion

        #region Standard Members

        //Make 5 users

        #region Member 1

        //Member 1
        $orgMemberUser = new User();
        $orgMemberUser->setUserId('FC22816A-314C-4647-88F9-ECD5CA4F47F2');
        $orgMemberUser->setFirstName('User');
        $orgMemberUser->setLastName('Two');
        $orgMemberUser->setEmail('orgMember1Aggregate@gmail.com');
        $orgMemberUser->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
        $orgMemberUser->setDate(new \DateTime('1969-11-28'));
        $orgMemberUser->setCountry('CA');
        $orgMemberUser->setCity('Saskatoon');
        $orgMemberUser->setStatus('active');
        $orgMemberUser->setIsActive(true);
        $manager->persist($orgMemberUser);

        //Make member a respondent
        $respondent = new Respondent();
        $respondent->setUser($orgMemberUser);
        $respondent->setAtRisk(true);
        $respondent->setMentalState("Hungry");
        $respondent->setStressLevel(4);
        $respondent->setEmergencyContact("Bob");
        $respondent->setDoctor("Travis");
        $manager->persist($respondent);

        //Give member 2 wellness records
        $wellnessRec1 = new Wellness();
        $wellnessRec1->setRespondent($respondent);
        $wellnessRec1->setMood(2);
        $wellnessRec1->setEnergy(3);
        $wellnessRec1->setThoughts(4);
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

        //Add member to org
        $orgMemStandard = new GroupMember();
        $orgMemStandard->setUser($orgMemberUser);
        $orgMemStandard->setStatus("active");
        $orgMemStandard->setDateJoined(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
        $orgMemStandard->setGroupRole("standard");
        $orgMemStandard->setGroup($org);
        $orgMemStandard->setGroupMemberId();
        $manager->persist($orgMemStandard);

        #endregion

        #region Member 2

        //Member 2
        $orgMemberUser = new User();
        $orgMemberUser->setUserId('FC22816A-314C-4647-88F9-ECD5CA4F47F3');
        $orgMemberUser->setFirstName('User');
        $orgMemberUser->setLastName('Three');
        $orgMemberUser->setEmail('orgMember2Aggregate@gmail.com');
        $orgMemberUser->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
        $orgMemberUser->setDate(new \DateTime('1969-11-28'));
        $orgMemberUser->setCountry('CA');
        $orgMemberUser->setCity('Saskatoon');
        $orgMemberUser->setStatus('active');
        $orgMemberUser->setIsActive(true);
        $manager->persist($orgMemberUser);

        //Make member a respondent
        $respondent = new Respondent();
        $respondent->setUser($orgMemberUser);
        $respondent->setAtRisk(true);
        $respondent->setMentalState("Hungry");
        $respondent->setStressLevel(4);
        $respondent->setEmergencyContact("Bob");
        $respondent->setDoctor("Travis");
        $manager->persist($respondent);

        //Give member 2 wellness records
        $wellnessRec1 = new Wellness();
        $wellnessRec1->setRespondent($respondent);
        $wellnessRec1->setMood(2);
        $wellnessRec1->setEnergy(3);
        $wellnessRec1->setThoughts(4);
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

        //Add member to org
        $orgMemStandard = new GroupMember();
        $orgMemStandard->setUser($orgMemberUser);
        $orgMemStandard->setStatus("active");
        $orgMemStandard->setDateJoined(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
        $orgMemStandard->setGroupRole("standard");
        $orgMemStandard->setGroup($org);
        $orgMemStandard->setGroupMemberId();
        $manager->persist($orgMemStandard);

        #endregion

        #region Member 3

        //Member 3
        $orgMemberUser = new User();
        $orgMemberUser->setUserId('FC22816A-314C-4647-88F9-ECD5CA4F47F4');
        $orgMemberUser->setFirstName('User');
        $orgMemberUser->setLastName('Four');
        $orgMemberUser->setEmail('orgMember3Aggregate@gmail.com');
        $orgMemberUser->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
        $orgMemberUser->setDate(new \DateTime('1969-11-28'));
        $orgMemberUser->setCountry('CA');
        $orgMemberUser->setCity('Saskatoon');
        $orgMemberUser->setStatus('active');
        $orgMemberUser->setIsActive(true);
        $manager->persist($orgMemberUser);

        //Make member a respondent
        $respondent = new Respondent();
        $respondent->setUser($orgMemberUser);
        $respondent->setAtRisk(true);
        $respondent->setMentalState("Hungry");
        $respondent->setStressLevel(4);
        $respondent->setEmergencyContact("Bob");
        $respondent->setDoctor("Travis");
        $manager->persist($respondent);

        //Give member 2 wellness records
        $wellnessRec1 = new Wellness();
        $wellnessRec1->setRespondent($respondent);
        $wellnessRec1->setMood(2);
        $wellnessRec1->setEnergy(3);
        $wellnessRec1->setThoughts(4);
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

        //Add member to org
        $orgMemStandard = new GroupMember();
        $orgMemStandard->setUser($orgMemberUser);
        $orgMemStandard->setStatus("active");
        $orgMemStandard->setDateJoined(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
        $orgMemStandard->setGroupRole("standard");
        $orgMemStandard->setGroup($org);
        $orgMemStandard->setGroupMemberId();
        $manager->persist($orgMemStandard);

        #endregion

        #region Member 4

        //Member 4
        $orgMemberUser = new User();
        $orgMemberUser->setUserId('FC22816A-314C-4647-88F9-ECD5CA4F47F5');
        $orgMemberUser->setFirstName('User');
        $orgMemberUser->setLastName('Five');
        $orgMemberUser->setEmail('orgMember4Aggregate@gmail.com');
        $orgMemberUser->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
        $orgMemberUser->setDate(new \DateTime('1969-11-28'));
        $orgMemberUser->setCountry('CA');
        $orgMemberUser->setCity('Saskatoon');
        $orgMemberUser->setStatus('active');
        $orgMemberUser->setIsActive(true);
        $manager->persist($orgMemberUser);

        //Make member a respondent
        $respondent = new Respondent();
        $respondent->setUser($orgMemberUser);
        $respondent->setAtRisk(true);
        $respondent->setMentalState("Hungry");
        $respondent->setStressLevel(4);
        $respondent->setEmergencyContact("Bob");
        $respondent->setDoctor("Travis");
        $manager->persist($respondent);

        //Give member 2 wellness records
        $wellnessRec1 = new Wellness();
        $wellnessRec1->setRespondent($respondent);
        $wellnessRec1->setMood(2);
        $wellnessRec1->setEnergy(3);
        $wellnessRec1->setThoughts(4);
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

        //Add member to org
        $orgMemStandard = new GroupMember();
        $orgMemStandard->setUser($orgMemberUser);
        $orgMemStandard->setStatus("active");
        $orgMemStandard->setDateJoined(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
        $orgMemStandard->setGroupRole("standard");
        $orgMemStandard->setGroup($org);
        $orgMemStandard->setGroupMemberId();
        $manager->persist($orgMemStandard);

        #endregion

        #region Member 5

        //Member 5
        $orgMemberUser = new User();
        $orgMemberUser->setUserId('FC22816A-314C-4647-88F9-ECD5CA4F47F6');
        $orgMemberUser->setFirstName('User');
        $orgMemberUser->setLastName('Six');
        $orgMemberUser->setEmail('orgMember5Aggregate@gmail.com');
        $orgMemberUser->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
        $orgMemberUser->setDate(new \DateTime('1969-11-28'));
        $orgMemberUser->setCountry('CA');
        $orgMemberUser->setCity('Saskatoon');
        $orgMemberUser->setStatus('active');
        $orgMemberUser->setIsActive(true);
        $manager->persist($orgMemberUser);

        //Make member a respondent
        $respondent = new Respondent();
        $respondent->setUser($orgMemberUser);
        $respondent->setAtRisk(true);
        $respondent->setMentalState("Hungry");
        $respondent->setStressLevel(4);
        $respondent->setEmergencyContact("Bob");
        $respondent->setDoctor("Travis");
        $manager->persist($respondent);

        //Give member 2 wellness records
        $wellnessRec1 = new Wellness();
        $wellnessRec1->setRespondent($respondent);
        $wellnessRec1->setMood(2);
        $wellnessRec1->setEnergy(3);
        $wellnessRec1->setThoughts(4);
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

        //Add member to org
        $orgMemStandard = new GroupMember();
        $orgMemStandard->setUser($orgMemberUser);
        $orgMemStandard->setStatus("active");
        $orgMemStandard->setDateJoined(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
        $orgMemStandard->setGroupRole("standard");
        $orgMemStandard->setGroup($org);
        $orgMemStandard->setGroupMemberId();
        $manager->persist($orgMemStandard);

        #endregion

        #endregion

        $manager->flush();
    }

    public function unload(ObjectManager $manager)
    {
        #region Remove Org Members

        //removing the org admin user
        $orgMember = $manager->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'ORG2816A-314C-4647-88F9-ECD5CA4F47F1:FC22816A-314C-4647-88F9-ECD5CA4F47F1'));

        if (!empty($orgMember))
        {
            $manager->remove($orgMember);
        }

        //removing member 1
        $orgMember = $manager->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'ORG2816A-314C-4647-88F9-ECD5CA4F47F1:FC22816A-314C-4647-88F9-ECD5CA4F47F2'));

        if (!empty($orgMember))
        {
            $manager->remove($orgMember);
        }

        //removing member 2
        $orgMember = $manager->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'ORG2816A-314C-4647-88F9-ECD5CA4F47F1:FC22816A-314C-4647-88F9-ECD5CA4F47F3'));

        if (!empty($orgMember))
        {
            $manager->remove($orgMember);
        }

        //removing member 3
        $orgMember = $manager->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'ORG2816A-314C-4647-88F9-ECD5CA4F47F1:FC22816A-314C-4647-88F9-ECD5CA4F47F4'));

        if (!empty($orgMember))
        {
            $manager->remove($orgMember);
        }

        //removing member 4
        $orgMember = $manager->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'ORG2816A-314C-4647-88F9-ECD5CA4F47F1:FC22816A-314C-4647-88F9-ECD5CA4F47F5'));

        if (!empty($orgMember))
        {
            $manager->remove($orgMember);
        }

        //removing member 5
        $orgMember = $manager->getRepository(GroupMember::class)
            ->findOneBy(array('groupMemberId'=>'ORG2816A-314C-4647-88F9-ECD5CA4F47F1:FC22816A-314C-4647-88F9-ECD5CA4F47F6'));

        if (!empty($orgMember))
        {
            $manager->remove($orgMember);
        }
        #endregion

        #region Remove Org

        $organization = $manager->getRepository(Group::class)
            ->findOneBy(array('groupID'=>'ORG2816A-314C-4647-88F9-ECD5CA4F47F1'));

        if (!empty($organization))
        {
            $manager->remove($organization);
        }
        #endregion

        #region Remove Wellness Records

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

        #endregion

        #region Remove Respondents

        //Remove respondent from member 1
        $respondent = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => 'FC22816A-314C-4647-88F9-ECD5CA4F47F2'));

        if (!empty($respondent))
        {
        	$manager->remove($respondent);
        }

        //Remove respondent from member 2
        $respondent = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => 'FC22816A-314C-4647-88F9-ECD5CA4F47F3'));

        if (!empty($respondent))
        {
        	$manager->remove($respondent);
        }

        //Remove respondent from member 3
        $respondent = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => 'FC22816A-314C-4647-88F9-ECD5CA4F47F4'));

        if (!empty($respondent))
        {
        	$manager->remove($respondent);
        }

        //Remove respondent from member 4
        $respondent = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => 'FC22816A-314C-4647-88F9-ECD5CA4F47F5'));

        if (!empty($respondent))
        {
        	$manager->remove($respondent);
        }

        //Remove respondent from member 5
        $respondent = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => 'FC22816A-314C-4647-88F9-ECD5CA4F47F6'));

        if (!empty($respondent))
        {
        	$manager->remove($respondent);
        }
        #endregion

        #region Remove Users

        //Remove org admin user
        $user = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => 'FC22816A-314C-4647-88F9-ECD5CA4F47F1'));

        if(!empty($user))
        {
            $manager->remove($user);
        }

        //Remove member 1
        $user = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => 'FC22816A-314C-4647-88F9-ECD5CA4F47F2'));

        if(!empty($user))
        {
            $manager->remove($user);
        }

        //Remove member 2
        $user = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => 'FC22816A-314C-4647-88F9-ECD5CA4F47F3'));

        if(!empty($user))
        {
            $manager->remove($user);
        }

        //Remove member 3
        $user = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => 'FC22816A-314C-4647-88F9-ECD5CA4F47F4'));

        if(!empty($user))
        {
            $manager->remove($user);
        }

        //Remove member 4
        $user = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => 'FC22816A-314C-4647-88F9-ECD5CA4F47F5'));

        if(!empty($user))
        {
            $manager->remove($user);
        }

        //Remove member 5
        $user = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => 'FC22816A-314C-4647-88F9-ECD5CA4F47F6'));

        if(!empty($user))
        {
            $manager->remove($user);
        }

        #endregion

        $manager->flush();
    }
}