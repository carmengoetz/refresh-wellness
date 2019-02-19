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

/**
 * JoinAGroupFixtures short summary.
 *
 * JoinAGroupFixtures description.
 *
 * @version 1.0
 * @author cst245
 */
class JoinAGroupFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        if (empty($manager->getRepository(User::class)->findOneBy(array('userID' => 'U4JoiningGR'))))
        {
            //Success user
            $user1 = new User();
            $user1->setUserId('U4JoiningGR');
            $user1->setFirstName('User');
            $user1->setLastName('One');
            $user1->setEmail('joingroupsuccess@email.com');
            $user1->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user1->setDate(new \DateTime('1969-11-28'));
            $user1->setCountry('CA');
            $user1->setCity('Saskatoon');
            $user1->setStatus('active');
            $user1->setIsActive(true);
            $manager->persist($user1);

            //User already member
            $user2 = new User();
            $user2->setUserId('U4JoiningGRAlreadyMember');
            $user2->setFirstName('User');
            $user2->setLastName('One');
            $user2->setEmail('joingroupalready@email.com');
            $user2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user2->setDate(new \DateTime('1969-11-28'));
            $user2->setCountry('CA');
            $user2->setCity('Saskatoon');
            $user2->setStatus('active');
            $user2->setIsActive(true);
            $manager->persist($user2);

            //Org Admin already admin
            $user3 = new User();
            $user3->setUserId('U4JoiningGRAdmin');
            $user3->setFirstName('User');
            $user3->setLastName('One');
            $user3->setEmail('joingroupadmin@email.com');
            $user3->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user3->setDate(new \DateTime('1969-11-28'));
            $user3->setCountry('CA');
            $user3->setCity('Saskatoon');
            $user3->setStatus('active');
            $user3->setIsActive(true);
            $manager->persist($user3);

            //Group for joining
            $group1 = new Group();
            $group1->setgroupId('GR4Joining1');
            $group1->setGroupName('Legs For Days');
            $group1->setGroupDesc('This is for testing purposes');
            $group1->setGroupType("standard");
            $manager->persist($group1);

            //Org for joining tests
            $group2 = new Group();
            $group2->setgroupId('ORG4Joining2');
            $group2->setGroupName('Legs For Weeks');
            $group2->setGroupDesc('This is for testing purposes');
            $group2->setGroupType("organization");
            $manager->persist($group2);

            //Org Admin
            $gmoa = new GroupMember();
            $gmoa->setGroup($group2);
            $gmoa->setUser($user3);
            $gmoa->setGroupRole("admin");
            $gmoa->setDateJoined(new \DateTime('1969-11-28'));
            $gmoa->setStatus("active");
            $gmoa->setGroupMemberId();
            $manager->persist($gmoa);

            //Group member for user 2
            $gm = new GroupMember();
            $gm->setGroup($group1);
            $gm->setUser($user2);
            $gm->setGroupRole("member");
            $gm->setDateJoined(new \DateTime('1969-11-28'));
            $gm->setStatus("active");
            $gm->setGroupMemberId();
            $manager->persist($gm);

            $manager->flush();

        }
    }

    public function unload(ObjectManager $manager)
    {
        //Remove users
        $user1 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => 'U4JoiningGR'));

        if (!empty($user1))
        {
            $manager->remove($user1);
        }

        $user2 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => 'U4JoiningGRAlreadyMember'));

        if (!empty($user2))
        {
            $manager->remove($user2);
        }

        $user3 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => 'U4JoiningGRAdmin'));

        if (!empty($user3))
        {
            $manager->remove($user3);
        }


        //Remove group memberships
        $groupMem1 = $manager->getRepository(GroupMember::class)
            ->findOneBy(array('user' => 'U4JoiningGR'));

        if (!empty($groupMem1))
        {
            $manager->remove($groupMem1);
        }

        $groupMem2 = $manager->getRepository(GroupMember::class)
            ->findOneBy(array('user' => 'U4JoiningGRAlreadyMember'));

        if (!empty($groupMem2))
        {
            $manager->remove($groupMem2);
        }

        $groupMem3 = $manager->getRepository(GroupMember::class)
            ->findOneBy(array('user' => 'U4JoiningGRAdmin'));

        if (!empty($groupMem3))
        {
            $manager->remove($groupMem3);
        }


        //Remove groups
        $group1 = $manager->getRepository(Group::class)
           ->findOneBy(array('groupID' => 'GR4Joining1'));

        if (!empty($group1))
        {
            $manager->remove($group1);
        }

        $group2 = $manager->getRepository(Group::class)
           ->findOneBy(array('groupID' => 'ORG4Joining2'));

        if (!empty($group2))
        {
            $manager->remove($group2);
        }

        $manager->flush();
    }

}