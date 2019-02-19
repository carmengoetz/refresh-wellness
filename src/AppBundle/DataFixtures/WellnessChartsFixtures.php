<?php

namespace AppBundle\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints\Date;
use AppBundle\Entity\Respondent;
use AppBundle\Entity\Group;
use AppBundle\Entity\WellnessProfessional;
use AppBundle\Entity\Relationship;
use AppBundle\Entity\Wellness;
use AppBundle\Entity\GroupMember;

class WellnessChartsFixtures extends Fixture
{
    private $respondentWellID;

    public function load(ObjectManager $manager)
    {
        //Create these users for the functional tests

        if (empty($manager->getRepository(User::class)->findOneBy(array('userID' => '4WellnessCharts1'))))
        {
            //Respondent user
            $user1 = new User();
            $user1->setUserId('4WellnessCharts1');
            $user1->setFirstName('User');
            $user1->setLastName('One');
            $user1->setEmail('cst.project5.refresh+wellnessCharts1@gmail.com');
            $user1->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user1->setDate(new \DateTime('1969-11-28'));
            $user1->setCountry('CA');
            $user1->setCity('Saskatoon');
            $user1->setStatus('active');
            $user1->setIsActive(true);
            $manager->persist($user1);

            //supporter user
            $user2 = new User();
            $user2->setUserId('4WellnessChartsSupporter1');
            $user2->setFirstName('User');
            $user2->setLastName('One');
            $user2->setEmail('cst.project5.refresh+wellnessCharts2@gmail.com');
            $user2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user2->setDate(new \DateTime('1969-11-28'));
            $user2->setCountry('CA');
            $user2->setCity('Saskatoon');
            $user2->setStatus('active');
            $user2->setIsActive(true);
            $manager->persist($user2);

            $relationship = new Relationship();
            $relationship->setUserIdTwo($user1);
            $relationship->setUserIdOne($user2);
            $relationship->setType('support');
            $relationship->setStatus("active");
            $relationship->setDateStarted(new \DateTime('1969-11-28'));
            $relationship->setRelationshipId();
            $manager->persist($relationship);

            //wellness pro user
            $user3 = new User();
            $user3->setUserId('4WellnessChartsWP1');
            $user3->setFirstName('User');
            $user3->setLastName('One');
            $user3->setEmail('cst.project5.refresh+wellnessCharts3@gmail.com');
            $user3->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user3->setDate(new \DateTime('1969-11-28'));
            $user3->setCountry('CA');
            $user3->setCity('Saskatoon');
            $user3->setStatus('active');
            $user3->setIsActive(true);
            $manager->persist($user3);


            //User with no wellness stats
            $user4 = new User();
            $user4->setUserId('4WellnessCharts2');
            $user4->setFirstName('User');
            $user4->setLastName('One');
            $user4->setEmail('cst.project5.refresh+wellnessCharts5@gmail.com');
            $user4->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user4->setDate(new \DateTime('1969-11-28'));
            $user4->setCountry('CA');
            $user4->setCity('Saskatoon');
            $user4->setStatus('active');
            $user4->setIsActive(true);
            $manager->persist($user4);

            $resp3 = new Respondent();
            $resp3->setUser($user4);
            $resp3->setAtRisk(false);
            $resp3->setMentalState('gud');
            $resp3->setStressLevel(5);
            $resp3->setDoctor("Dr. Feelgud");
            $resp3->setEmergencyContact('8675309');
            $manager->persist($resp3);

            

            //$wellnessRec1 = new Wellness();
            //$wellnessRec1->setRespondent($resp3);
            //$wellnessRec1->setMood(2);
            //$wellnessRec1->setEnergy(2);
            //$wellnessRec1->setThoughts(2);
            //$wellnessRec1->setSleep(2);
            //$wellnessRec1->setDate(date("Y-m-d"));
            //$manager->persist($wellnessRec1);





            $relationship2 = new Relationship();
            $relationship2->setUserIdTwo($user3);
            $relationship2->setUserIdOne($user4);
            $relationship2->setType('wellness professional');
            $relationship2->setStatus("active");
            $relationship2->setDateStarted(new \DateTime('1969-11-28'));
            $relationship2->setRelationshipId();
            $manager->persist($relationship2);


            $wp = new WellnessProfessional();
            $wp->setPracticeName("Bob Loblaw's Therapy Emporium");
            $wp->setUser($user3);
            $manager->persist($wp);

            $prel = new Relationship();
            $prel->setUserIdOne($user1);
            $prel->setUserIdTwo($user3);
            $prel->setType('wellness professional');
            $prel->setStatus("active");
            $prel->setDateStarted(new \DateTime('1969-11-28'));
            $prel->setRelationshipId();
            $manager->persist($prel);

            //org admin user

            $oa = new User();
            $oa->setUserId('4WellnessChartsOA1');
            $oa->setFirstName('User');
            $oa->setLastName('One');
            $oa->setEmail('cst.project5.refresh+wellnessCharts4@gmail.com');
            $oa->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $oa->setDate(new \DateTime('1969-11-28'));
            $oa->setCountry('CA');
            $oa->setCity('Saskatoon');
            $oa->setStatus('active');
            $oa->setIsActive(true);
            $manager->persist($oa);

            //org

            $group = new Group();
            $group->setgroupId('groupForCharts');
            $group->setGroupName("Chart group");
            $group->setGroupDesc("Chart group");
            $group->setGroupType("organization");
            $manager->persist($group);


            //make respondent member of org and org admin admin of org
            $gm = new GroupMember();
            $gm->setGroup($group);
            $gm->setUser($user1);
            $gm->setGroupRole("member");
            $gm->setDateJoined(new \DateTime('1969-11-28'));
            $gm->setStatus("active");
            $gm->setGroupMemberId();
            $manager->persist($gm);

            $gmoa = new GroupMember();
            $gmoa->setGroup($group);
            $gmoa->setUser($oa);
            $gmoa->setGroupRole("admin");
            $gmoa->setDateJoined(new \DateTime('1969-11-28'));
            $gmoa->setStatus("active");
            $gmoa->setGroupMemberId();
            $manager->persist($gmoa);


            /******************** ADD FIXTURES FOR WELLNESS QUESTIONS */
            $resp1 = new Respondent();
            $resp1->setUser($user1);
            $resp1->setAtRisk(false);
            $resp1->setMentalState('gud');
            $resp1->setStressLevel(5);
            $resp1->setDoctor("Dr. Feelgud");
            $resp1->setEmergencyContact('8675309');

            $wellnessRec1 = new Wellness();
            $wellnessRec1->setRespondent($resp1);
            $wellnessRec1->setMood(2);
            $wellnessRec1->setEnergy(2);
            $wellnessRec1->setThoughts(2);
            $wellnessRec1->setSleep(2);
            $wellnessRec1->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec1);

            $manager->persist($resp1);
            #region Wellness
            //Make a whole bunch of wellness records
            $wellnessRecord1 = new Wellness();
            $wellnessRecord1->setRespondent($resp1);
            $wellnessRecord1->setDate("Jan 25, 2018");
            $wellnessRecord1->setMood(7);
            $wellnessRecord1->setEnergy(3);
            $wellnessRecord1->setSleep(2);
            $wellnessRecord1->setThoughts(9);

            $manager->persist($wellnessRecord1);

            $wellnessRecord2 = new Wellness();
            $wellnessRecord2->setRespondent($resp1);
            $wellnessRecord2->setDate("Jan 26, 2018");
            $wellnessRecord2->setMood(2);
            $wellnessRecord2->setEnergy(6);
            $wellnessRecord2->setSleep(9);
            $wellnessRecord2->setThoughts(3);

            $manager->persist($wellnessRecord2);

            $wellnessRecord3 = new Wellness();
            $wellnessRecord3->setRespondent($resp1);
            $wellnessRecord3->setDate("Jan 27, 2018");
            $wellnessRecord3->setMood(2);
            $wellnessRecord3->setEnergy(3);
            $wellnessRecord3->setSleep(4);
            $wellnessRecord3->setThoughts(2);

            $manager->persist($wellnessRecord3);

            $wellnessRecord4 = new Wellness();
            $wellnessRecord4->setRespondent($resp1);
            $wellnessRecord4->setDate("Jan 29, 2018");
            $wellnessRecord4->setMood(1);
            $wellnessRecord4->setEnergy(10);
            $wellnessRecord4->setSleep(5);
            $wellnessRecord4->setThoughts(7);

            $manager->persist($wellnessRecord4);

            $wellnessRecord5 = new Wellness();
            $wellnessRecord5->setRespondent($resp1);
            $wellnessRecord5->setDate("Jan 30, 2018");
            $wellnessRecord5->setMood(2);
            $wellnessRecord5->setEnergy(0);
            $wellnessRecord5->setSleep(10);
            $wellnessRecord5->setThoughts(8);

            $manager->persist($wellnessRecord5);

            $wellnessRecord6 = new Wellness();
            $wellnessRecord6->setRespondent($resp1);
            $wellnessRecord6->setDate("Jan 31, 2018");
            $wellnessRecord6->setMood(6);
            $wellnessRecord6->setEnergy(6);
            $wellnessRecord6->setSleep(4);
            $wellnessRecord6->setThoughts(3);

            $manager->persist($wellnessRecord6);

            $wellnessRecord7 = new Wellness();
            $wellnessRecord7->setRespondent($resp1);
            $wellnessRecord7->setDate("Jan 24, 2018");
            $wellnessRecord7->setMood(2);
            $wellnessRecord7->setEnergy(8);
            $wellnessRecord7->setSleep(7);
            $wellnessRecord7->setThoughts(8);

            $manager->persist($wellnessRecord7);

            $wellnessRecord8 = new Wellness();
            $wellnessRecord8->setRespondent($resp1);
            $wellnessRecord8->setDate("Jan 23, 2018");
            $wellnessRecord8->setMood(7);
            $wellnessRecord8->setEnergy(7);
            $wellnessRecord8->setSleep(6);
            $wellnessRecord8->setThoughts(4);

            $manager->persist($wellnessRecord8);
            #endregion

            $manager->flush();

            $resp2 = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '4WellnessCharts1'));

            if (!empty($resp2))
            {
                $this->respondentWellID = $resp2->getRespondentID();
            }
        }

    }

    public function unload(ObjectManager $manager)
    {
        $user1 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '4WellnessCharts1'));

        if (!empty($user1))
        {
            $manager->remove($user1);
        }

        $user2 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '4WellnessChartsSupporter1'));

        if (!empty($user2))
        {
            $manager->remove($user2);
        }

        $user3 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '4WellnessChartsWP1'));

        if (!empty($user3))
        {
            $manager->remove($user3);
        }

        $oa = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '4WellnessChartsOA1'));

        if (!empty($oa))
        {
            $manager->remove($oa);
        }

        $ns = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '4WellnessCharts2'));

        if (!empty($ns))
        {
            $manager->remove($ns);
        }

        $resp2 = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '4WellnessCharts2'));

        if (!empty($resp2))
        {
            $manager->remove($resp2);
        }


        $resp1 = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '4WellnessCharts1'));

        if (!empty($resp1))
        {
            $manager->remove($resp1);
        }

        $rel1 = $manager->getRepository(Relationship::class)
            ->findOneBy(array('relationshipId' => '4WellnessChartsSupporter1:4WellnessCharts1:support'));

        if (!empty($rel1))
        {
            $manager->remove($rel1);
        }

        $rel1 = $manager->getRepository(Relationship::class)
            ->findOneBy(array('relationshipId' => '4WellnessCharts1:4WellnessChartsWP1:wellnessprofessional'));

        if (!empty($rel1))
        {
            $manager->remove($rel1);
        }

        $rel2 = $manager->getRepository(Relationship::class)
            ->findOneBy(array('relationshipId' => '4WellnessCharts2:4WellnessChartsWP1:wellnessprofessional'));

        if (!empty($rel2))
        {
            $manager->remove($rel2);
        }



        $wp = $manager->getRepository(WellnessProfessional::class)
            ->findOneBy(array('user' => '4WellnessChartsWP1'));

        if (!empty($wp))
        {
            $manager->remove($wp);
        }

        $group = $manager->getRepository(Group::class)
            ->findOneBy(array('groupID' => 'groupForCharts'));

        if (!empty($group))
        {
            $manager->remove($group);
        }

        $groupMem1 = $manager->getRepository(GroupMember::class)
            ->findOneBy(array('user' => '4WellnessCharts1'));

        if (!empty($groupMem1))
        {
            $manager->remove($groupMem1);
        }

        $groupMem2 = $manager->getRepository(GroupMember::class)
            ->findOneBy(array('user' => '4WellnessChartsOA1'));

        if (!empty($groupMem2))
        {
            $manager->remove($groupMem2);
        }

        $wellness = $manager->getRepository(Wellness::class)
            ->findBy(array("respondent"=>$this->respondentWellID));

        if(!empty($wellness))
        {
            foreach ($wellness as $well)
            {
                $manager->remove($well);
            }
        }

        $manager->flush();
    }


}