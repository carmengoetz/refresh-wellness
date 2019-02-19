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

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Create these users for the functional tests

        if (empty($manager->getRepository(User::class)->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7'))))
        {
            $user1 = new User();
            $user1->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9BC7');
            $user1->setFirstName('User');
            $user1->setLastName('One');
            $user1->setEmail('cst.project5.refresh+test1@gmail.com');
            $user1->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user1->setDate(new \DateTime('1969-11-28'));
            $user1->setCountry('CA');
            $user1->setCity('Saskatoon');
            $user1->setStatus('active');
            $user1->setIsActive(true);
            $manager->persist($user1);

            $user2 = new User();
            $user2->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9BC6');
            $user2->setFirstName('User');
            $user2->setLastName('Two');
            $user2->setEmail('imtigerwoods@yahoo.ca');
            $user2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user2->setDate(new \DateTime('1969-11-28'));
            $user2->setCountry('CA');
            $user2->setCity('Calgary');
            $user2->setStatus('active');
            $user2->setIsActive(true);
            $manager->persist($user2);

            $user3 = new User();
            $user3->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9BC8');
            $user3->setFirstName('User');
            $user3->setLastName('Three');
            $user3->setEmail('cst.project5.refresh+test3@gmail.com');
            $user3->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user3->setDate(new \DateTime('1969-11-28'));
            $user3->setCountry('CA');
            $user3->setCity('Winnipeg');
            $user3->setStatus('inactive');
            $user3->setIsActive(false);
            $manager->persist($user3);

            $user4 = new User();
            $user4->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9BC9');
            $user4->setFirstName('User');
            $user4->setLastName('Four');
            $user4->setEmail('cst.project5.refresh+test4@gmail.com');
            $user4->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user4->setDate(new \DateTime('1969-11-28'));
            $user4->setCountry('CA');
            $user4->setCity('Winnipeg');
            $user4->setStatus('active');
            $user4->setIsActive(false);
            $manager->persist($user4);

            /******************** ADD FIXTURES FOR WELLNESS QUESTIONS */
            $resp1 = new Respondent();
            $resp1->setUser($user2);
            $resp1->setAtRisk(false);
            $resp1->setMentalState('gud');
            $resp1->setStressLevel(5);
            $resp1->setDoctor("Dr. Feelgud");
            $resp1->setEmergencyContact('8675309');

            $manager->persist($resp1);



            $manager->flush();
        }

    }

    public function loadGroup(ObjectManager $manager)
    {
        if (empty($manager->getRepository(Group::class)->findOneBy(array('groupID' => '1'))))
        {
            $group1 = new Group();
            $group1->setgroupId('1');
            $group1->setGroupName('Project Refresh');
            $group1->setGroupDesc('This is for testing purposes');
            $group1->setGroupType("standard");
            $manager->persist($group1);
        }
    }

    public function loadWP(ObjectManager $manager)
    {
        if (empty($manager->getRepository(User::class)->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9B01'))))
        {
            $user1 = new User();
            $user1->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B01');
            $user1->setFirstName('Bob');
            $user1->setLastName('Bob');
            $user1->setEmail('cst.project5.refresh+loggedProf@gmail.com');
            $user1->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user1->setDate(new \DateTime('1969-11-28'));
            $user1->setCountry('CA');
            $user1->setCity('Saskatoon');
            $user1->setStatus('active');
            $user1->setIsActive(false);
            $manager->persist($user1);

            $wp1 = new WellnessProfessional();
            $wp1->setUser($user1);
            $wp1->setPracticeName("Bob's Therapy Emporium");
            $wp1->setContactNumber('3061234567');
            $wp1->setContactEmail("bob@therapyemporium.ca");
            $manager->persist($wp1);

            $user2 = new User();
            $user2->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B02');
            $user2->setFirstName('Clara');
            $user2->setLastName('Clara');
            $user2->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user2->setDate(new \DateTime('1969-11-28'));
            $user2->setCountry('CA');
            $user2->setCity('Saskatoon');
            $user2->setStatus('active');
            $user2->setIsActive(false);
            $manager->persist($user2);

            $wp2 = new WellnessProfessional();
            $wp2->setUser($user2);
            $wp2->setPracticeName("Clara's Crystal Healing");
            $wp2->setContactEmail("clara@crystalhealth.ca");
            $wp2->setWebsite('http://www.crystalhealth.ca');
            $manager->persist($wp2);

            $user3 = new User();
            $user3->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B03');
            $user3->setFirstName('Carmen');
            $user3->setLastName('Goetz');
            $user3->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user3->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user3->setDate(new \DateTime('1969-11-28'));
            $user3->setCountry('CA');
            $user3->setCity('Saskatoon');
            $user3->setStatus('active');
            $user3->setIsActive(false);
            $manager->persist($user3);

            $wp3 = new WellnessProfessional();
            $wp3->setUser($user3);
            $wp3->setPracticeName("Divine Mindfulness");
            $wp3->setContactNumber('3061234567');
            $wp3->setContactEmail("info@divinemindfulness.org");
            $wp3->setWebsite('http://www.divinemindfulness.org');
            $manager->persist($wp3);

            $user4 = new User();
            $user4->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B04');
            $user4->setFirstName('Graham');
            $user4->setLastName('Pyett');
            $user4->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user4->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user4->setDate(new \DateTime('1969-11-28'));
            $user4->setCountry('CA');
            $user4->setCity('Winnipeg');
            $user4->setStatus('active');
            $user4->setIsActive(false);
            $manager->persist($user4);

            $wp4 = new WellnessProfessional();
            $wp4->setUser($user4);
            $wp4->setPracticeName("Protage & Main Psychotherapy");
            $wp4->setContactNumber('2041234567');
            $wp4->setContactEmail("contact@pandmtherapy.com");
            $wp4->setWebsite('http://www.pandmtherapy.com');
            $manager->persist($wp4);

            $user5 = new User();
            $user5->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B05');
            $user5->setFirstName('Abigail');
            $user5->setLastName('Williamson');
            $user5->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user5->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user5->setDate(new \DateTime('1969-11-28'));
            $user5->setCountry('CA');
            $user5->setCity('Winnipeg');
            $user5->setStatus('active');
            $user5->setIsActive(false);
            $manager->persist($user5);

            $wp5 = new WellnessProfessional();
            $wp5->setUser($user5);
            $wp5->setPracticeName("Counselling by the Forks");
            $wp5->setContactNumber('2041234567');
            $wp5->setContactEmail("info@forkscounselling.net");
            $wp5->setWebsite('http://www.forkscounselling.net');
            $manager->persist($wp5);

            $user6 = new User();
            $user6->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B06');
            $user6->setFirstName('Draden');
            $user6->setLastName('Sawkey');
            $user6->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user6->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user6->setDate(new \DateTime('1969-11-28'));
            $user6->setCountry('CA');
            $user6->setCity('Winnipeg');
            $user6->setStatus('active');
            $user6->setIsActive(false);
            $manager->persist($user6);

            $wp6 = new WellnessProfessional();
            $wp6->setUser($user6);
            $wp6->setPracticeName("Mint Counsellors");
            $wp6->setContactNumber('2042234567');
            $wp6->setContactEmail("info@mintcounsellors.co.uk");
            $wp6->setWebsite('http://www.mintcounsellors.co.uk');
            $manager->persist($wp6);

            $user7 = new User();
            $user7->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B07');
            $user7->setFirstName('Chrisp');
            $user7->setLastName('Chrisp');
            $user7->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user7->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user7->setDate(new \DateTime('1969-11-28'));
            $user7->setCountry('CA');
            $user7->setCity('Winnipeg');
            $user7->setStatus('active');
            $user7->setIsActive(false);
            $manager->persist($user7);

            $wp7 = new WellnessProfessional();
            $wp7->setUser($user7);
            $wp7->setPracticeName("Retail Therapy");
            $wp7->setContactNumber('2041234567');
            $wp7->setContactEmail("info@therapy.retail");
            $wp7->setWebsite('http://www.therapy.retail');
            $manager->persist($wp7);

            $user8 = new User();
            $user8->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B08');
            $user8->setFirstName('Graham');
            $user8->setLastName('Saufert');
            $user8->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user8->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user8->setDate(new \DateTime('1969-11-28'));
            $user8->setCountry('CA');
            $user8->setCity('Winnipeg');
            $user8->setStatus('active');
            $user8->setIsActive(false);
            $manager->persist($user8);

            $wp8 = new WellnessProfessional();
            $wp8->setUser($user8);
            $wp8->setPracticeName("Churchill Brain Doctors");
            $wp8->setContactNumber('2041234567');
            $wp8->setContactEmail("info@churchillbrainmd.com");
            $wp8->setWebsite('http://www.churchillbrainmd.com');
            $manager->persist($wp8);

            $user9 = new User();
            $user9->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B09');
            $user9->setFirstName('Wade');
            $user9->setLastName('Lahoda');
            $user9->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user9->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user9->setDate(new \DateTime('1969-11-28'));
            $user9->setCountry('CA');
            $user9->setCity('Winnipeg');
            $user9->setStatus('active');
            $user9->setIsActive(false);
            $manager->persist($user9);

            $wp9 = new WellnessProfessional();
            $wp9->setUser($user9);
            $wp9->setPracticeName("The Jets");
            $wp9->setContactNumber('2041234567');
            $wp9->setContactEmail("info@thejets.nhl");
            $wp9->setWebsite('http://www.thejets.nhl');
            $manager->persist($wp9);

            $user10 = new User();
            $user10->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B10');
            $user10->setFirstName('Ernesto');
            $user10->setLastName('Basoalto');
            $user10->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user10->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user10->setDate(new \DateTime('1969-11-28'));
            $user10->setCountry('CA');
            $user10->setCity('Winnipeg');
            $user10->setStatus('active');
            $user10->setIsActive(false);
            $manager->persist($user10);

            $wp10 = new WellnessProfessional();
            $wp10->setUser($user10);
            $wp10->setPracticeName("Therapy @ the Human Rights Musem");
            $wp10->setContactNumber('2041234567');
            $wp10->setContactEmail("info@therapyhr.ca");
            $wp10->setWebsite('http://www.therapyhr.ca');
            $manager->persist($wp10);

            $user11 = new User();
            $user11->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B11');
            $user11->setFirstName('Rick');
            $user11->setLastName('Caron');
            $user11->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user11->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user11->setDate(new \DateTime('1969-11-28'));
            $user11->setCountry('CA');
            $user11->setCity('Winnipeg');
            $user11->setStatus('active');
            $user11->setIsActive(false);
            $manager->persist($user11);

            $wp11 = new WellnessProfessional();
            $wp11->setUser($user11);
            $wp11->setPracticeName("Red River Therapy");
            $wp11->setContactNumber('2041234567');
            $wp11->setContactEmail("redriver@mts.mb.ca");
            $wp11->setWebsite('http://www.geocities.org/red-river-therapy');
            $manager->persist($wp11);

            $user12 = new User();
            $user12->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B12');
            $user12->setFirstName('Ron');
            $user12->setLastName('New');
            $user12->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user12->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user12->setDate(new \DateTime('1969-11-28'));
            $user12->setCountry('CA');
            $user12->setCity('Winnipeg');
            $user12->setStatus('active');
            $user12->setIsActive(false);
            $manager->persist($user12);

            $wp12 = new WellnessProfessional();
            $wp12->setUser($user12);
            $wp12->setPracticeName("Seasonal Therapy");
            $wp12->setContactNumber('2041234567');
            $wp12->setContactEmail("info@seasonaltherapypartners.com");
            $wp12->setWebsite('http://www.seasonaltherapypartners.com');
            $manager->persist($wp12);

            $user13 = new User();
            $user13->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B13');
            $user13->setFirstName('Ben');
            $user13->setLastName('Benson');
            $user13->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user13->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user13->setDate(new \DateTime('1969-11-28'));
            $user13->setCountry('CA');
            $user13->setCity('Winnipeg');
            $user13->setStatus('active');
            $user13->setIsActive(false);
            $manager->persist($user13);

            $wp13 = new WellnessProfessional();
            $wp13->setUser($user13);
            $wp13->setPracticeName("Blue Bombers Therapy");
            $wp13->setContactNumber('2041234567');
            $wp13->setContactEmail("info@bbtherapy.com");
            $wp13->setWebsite('http://www.bbtherapy.com');
            $manager->persist($wp13);

            $user14 = new User();
            $user14->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B14');
            $user14->setFirstName('Shane');
            $user14->setLastName('McDonald');
            $user14->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user14->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user14->setDate(new \DateTime('1969-11-28'));
            $user14->setCountry('CA');
            $user14->setCity('Winnipeg');
            $user14->setStatus('active');
            $user14->setIsActive(false);
            $manager->persist($user14);

            $wp14 = new WellnessProfessional();
            $wp14->setUser($user14);
            $wp14->setPracticeName("Mainstreet Counselling Partners");
            $wp14->setContactNumber('2041234567');
            $wp14->setContactEmail("info@mainstreetcounselling.net");
            $wp14->setWebsite('http://www.mainstreetcounselling.net');
            $manager->persist($wp14);

            $user15 = new User();
            $user15->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B15');
            $user15->setFirstName('Sharon');
            $user15->setLastName('McDonals');
            $user15->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user15->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user15->setDate(new \DateTime('1969-11-28'));
            $user15->setCountry('CA');
            $user15->setCity('Winnipeg');
            $user15->setStatus('active');
            $user15->setIsActive(false);
            $manager->persist($user15);

            $wp15 = new WellnessProfessional();
            $wp15->setUser($user15);
            $wp15->setPracticeName("Voyageur Counselling Services");
            $wp15->setContactNumber('2041234567');
            $wp15->setContactEmail("info@voyageurs.ca");
            $wp15->setWebsite('http://www.voyageurs.ca');
            $manager->persist($wp15);

            //User added for testing adding wellness professionals
            $user16 = new User();
            $user16->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B16');
            $user16->setFirstName('Fred');
            $user16->setLastName('Flintstone');
            $user16->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user16->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user16->setDate(new \DateTime('1969-11-28'));
            $user16->setCountry('CA');
            $user16->setCity('Winnipeg');
            $user16->setStatus('inactive');
            $user16->setIsActive(false);
            $manager->persist($user16);

            $wp16 = new WellnessProfessional();
            $wp16->setUser($user16);
            $wp16->setPracticeName("Voyageur Counselling Services");
            $wp16->setContactNumber('2041234568');
            $wp16->setContactEmail("info@voyageurs.com");
            $wp16->setWebsite('www.voyageurs.com');
            $manager->persist($wp16);

            $manager->flush();

        }
    }

    public function addPatientRel(ObjectManager $manager)
    {
        //Creating a Wellness Professional to have a max list of patients (functionality not yet
        //implemened)
        if (empty($manager->getRepository(User::class)->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9B17'))))
        {
            $user17 = new User();
            $user17->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9B17');
            $user17->setFirstName('Bob');
            $user17->setLastName('Bob');
            $user17->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user17->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
            $user17->setDate(new \DateTime('1969-11-28'));
            $user17->setCountry('CA');
            $user17->setCity('Saskatoon');
            $user17->setStatus('active');
            $user17->setIsActive(false);
            $manager->persist($user17);

            $wp17 = new WellnessProfessional();
            $wp17->setUser($user17);
            $wp17->setPracticeName("Bob's Therapy Emporium");
            $wp17->setContactNumber('3061234567');
            $wp17->setContactEmail("bob@therapyemporium.ca");
            $manager->persist($wp17);

        //Creation of 10 patients for the wellness professional
        #region Patients
        $patient1 = new User();
        $patient1->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P01');
        $patient1->setFirstName('User');
        $patient1->setLastName('One');
        $patient1->setEmail('cst.project5.refresh+tet1@gmail.com');
        $patient1->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
        $patient1->setDate(new \DateTime('1969-11-28'));
        $patient1->setCountry('CA');
        $patient1->setCity('Saskatoon');
        $patient1->setStatus('active');
        $patient1->setIsActive(true);
        $manager->persist($patient1);

        $patient2 = new User();
        $patient2->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P02');
        $patient2->setFirstName('User');
        $patient2->setLastName('One');
        $patient2->setEmail('cst.project5.refresh+test@gmail.com');
        $patient2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
        $patient2->setDate(new \DateTime('1969-11-28'));
        $patient2->setCountry('CA');
        $patient2->setCity('Saskatoon');
        $patient2->setStatus('active');
        $patient2->setIsActive(true);
        $manager->persist($patient2);

        $patient3 = new User();
        $patient3->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P03');
        $patient3->setFirstName('User');
        $patient3->setLastName('One');
        $patient3->setEmail('cst.project5.refresh+test1@mail.com');
        $patient3->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
        $patient3->setDate(new \DateTime('1969-11-28'));
        $patient3->setCountry('CA');
        $patient3->setCity('Saskatoon');
        $patient3->setStatus('active');
        $patient3->setIsActive(true);
        $manager->persist($patient3);

        $patient4 = new User();
        $patient4->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P04');
        $patient4->setFirstName('User');
        $patient4->setLastName('One');
        $patient4->setEmail('cst.project5.refresh+test1@gail.com');
        $patient4->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
        $patient4->setDate(new \DateTime('1969-11-28'));
        $patient4->setCountry('CA');
        $patient4->setCity('Saskatoon');
        $patient4->setStatus('active');
        $patient4->setIsActive(true);
        $manager->persist($patient4);

        $patient5 = new User();
        $patient5->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P05');
        $patient5->setFirstName('User');
        $patient5->setLastName('One');
        $patient5->setEmail('cst.project5.refresh+test1@gmil.com');
        $patient5->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
        $patient5->setDate(new \DateTime('1969-11-28'));
        $patient5->setCountry('CA');
        $patient5->setCity('Saskatoon');
        $patient5->setStatus('active');
        $patient5->setIsActive(true);
        $manager->persist($patient5);

        $patient6 = new User();
        $patient6->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P06');
        $patient6->setFirstName('User');
        $patient6->setLastName('One');
        $patient6->setEmail('cst.project5.refresh+test1@gmal.com');
        $patient6->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
        $patient6->setDate(new \DateTime('1969-11-28'));
        $patient6->setCountry('CA');
        $patient6->setCity('Saskatoon');
        $patient6->setStatus('active');
        $patient6->setIsActive(true);
        $manager->persist($patient6);

        $patient7 = new User();
        $patient7->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P07');
        $patient7->setFirstName('User');
        $patient7->setLastName('One');
        $patient7->setEmail('cst.project5.refresh+test1@gmai.com');
        $patient7->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
        $patient7->setDate(new \DateTime('1969-11-28'));
        $patient7->setCountry('CA');
        $patient7->setCity('Saskatoon');
        $patient7->setStatus('active');
        $patient7->setIsActive(true);
        $manager->persist($patient7);

            $patient8 = new User();
            $patient8->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P08');
            $patient8->setFirstName('User');
            $patient8->setLastName('One');
            $patient8->setEmail('cst.project5.refresh+test1@gmail.om');
            $patient8->setPassword('$2y$13$ouajAK4OsUqSl47uaz0nwOVDPeQNskHOne8e9Jo.oZfS/4ff1D8.e');
            $patient8->setDate(new \DateTime('1969-11-28'));
            $patient8->setCountry('CA');
            $patient8->setCity('Saskatoon');
            $patient8->setStatus('active');
            $patient8->setIsActive(true);
            $manager->persist($patient8);

            $patient9 = new User();
            $patient9->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P09');
            $patient9->setFirstName('User');
            $patient9->setLastName('One');
            $patient9->setEmail('cst.project5.refresh+test1@gmail.cm');
            $patient9->setPassword('$2y$13$ouajAK4OsUqSl47uaz0nwOVDPeQNskHOne8e9Jo.oZfS/4ff1D8.e');
            $patient9->setDate(new \DateTime('1969-11-28'));
            $patient9->setCountry('CA');
            $patient9->setCity('Saskatoon');
            $patient9->setStatus('active');
            $patient9->setIsActive(true);
            $manager->persist($patient9);

            $patient10 = new User();
            $patient10->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P00');
            $patient10->setFirstName('User');
            $patient10->setLastName('One');
            $patient10->setEmail('cst.project5.refresh+test1@gmail.co');
            $patient10->setPassword('$2y$13$ouajAK4OsUqSl47uaz0nwOVDPeQNskHOne8e9Jo.oZfS/4ff1D8.e');
            $patient10->setDate(new \DateTime('1969-11-28'));
            $patient10->setCountry('CA');
            $patient10->setCity('Saskatoon');
            $patient10->setStatus('active');
            $patient10->setIsActive(true);
            $manager->persist($patient10);

            #endregion

            $manager->flush();

            //"Paient" relationship creation

            $wellnessProf = $manager
                      ->getRepository(User::class)
                      ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9B17']);

            //Loops through each user and has them add the Wellness Professional as a
            //wellness professional to fill the patient list.
            for ($i = 0; $i < 10; $i++)
            {
                $patient = $manager
                      ->getRepository(User::class)
                      ->findOneBy(['userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9P0'.$i]);

                $relToAdd = new Relationship();

                $relToAdd->setUserIdOne($patient);
                $relToAdd->setUserIdTwo($wellnessProf);

                $relToAdd->setStatus("pending");

                $relToAdd->setType('wellness professional');

                //have to set after as the relationship id now contains the type
                $relToAdd->setRelationshipId();

                $relToAdd->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));

                $manager->persist($relToAdd);

                $manager->flush();
            }
        }
    }

    public function loadClients(ObjectManager $manager)
    {
        #region Clients
        $patient1 = new User();
        $patient1->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P01');
        $patient1->setFirstName('User');
        $patient1->setLastName('One');
        $patient1->setEmail('cst.projct5.refresh+tet1@gmail.com');
        $patient1->setPassword('$2y$13$ouajAK4OsUqSl47uaz0nwOVDPeQNskHOne8e9Jo.oZfS/4ff1D8.e');
        $patient1->setDate(new \DateTime('1969-11-28'));
        $patient1->setCountry('CA');
        $patient1->setCity('Saskatoon');
        $patient1->setStatus('active');
        $patient1->setIsActive(true);
        $manager->persist($patient1);

        $patient2 = new User();
        $patient2->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P02');
        $patient2->setFirstName('User');
        $patient2->setLastName('One');
        $patient2->setEmail('cst.project5.refresh+test@gmail.com');
        $patient2->setPassword('$2y$13$ouajAK4OsUqSl47uaz0nwOVDPeQNskHOne8e9Jo.oZfS/4ff1D8.e');
        $patient2->setDate(new \DateTime('1969-11-28'));
        $patient2->setCountry('CA');
        $patient2->setCity('Saskatoon');
        $patient2->setStatus('active');
        $patient2->setIsActive(true);
        $manager->persist($patient2);

        $patient3 = new User();
        $patient3->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P03');
        $patient3->setFirstName('User');
        $patient3->setLastName('One');
        $patient3->setEmail('cst.project5.refresh+test1@mail.com');
        $patient3->setPassword('$2y$13$ouajAK4OsUqSl47uaz0nwOVDPeQNskHOne8e9Jo.oZfS/4ff1D8.e');
        $patient3->setDate(new \DateTime('1969-11-28'));
        $patient3->setCountry('CA');
        $patient3->setCity('Saskatoon');
        $patient3->setStatus('active');
        $patient3->setIsActive(true);
        $manager->persist($patient3);

        $patient4 = new User();
        $patient4->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P04');
        $patient4->setFirstName('User');
        $patient4->setLastName('One');
        $patient4->setEmail('cst.project5.refresh+test1@gail.com');
        $patient4->setPassword('$2y$13$ouajAK4OsUqSl47uaz0nwOVDPeQNskHOne8e9Jo.oZfS/4ff1D8.e');
        $patient4->setDate(new \DateTime('1969-11-28'));
        $patient4->setCountry('CA');
        $patient4->setCity('Saskatoon');
        $patient4->setStatus('active');
        $patient4->setIsActive(true);
        $manager->persist($patient4);

        $patient5 = new User();
        $patient5->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P05');
        $patient5->setFirstName('User');
        $patient5->setLastName('One');
        $patient5->setEmail('cst.project5.refresh+test1@gmil.com');
        $patient5->setPassword('$2y$13$ouajAK4OsUqSl47uaz0nwOVDPeQNskHOne8e9Jo.oZfS/4ff1D8.e');
        $patient5->setDate(new \DateTime('1969-11-28'));
        $patient5->setCountry('CA');
        $patient5->setCity('Saskatoon');
        $patient5->setStatus('active');
        $patient5->setIsActive(true);
        $manager->persist($patient5);

        $patient6 = new User();
        $patient6->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P06');
        $patient6->setFirstName('User');
        $patient6->setLastName('One');
        $patient6->setEmail('cst.project5.refresh+test1@gmal.com');
        $patient6->setPassword('$2y$13$ouajAK4OsUqSl47uaz0nwOVDPeQNskHOne8e9Jo.oZfS/4ff1D8.e');
        $patient6->setDate(new \DateTime('1969-11-28'));
        $patient6->setCountry('CA');
        $patient6->setCity('Saskatoon');
        $patient6->setStatus('active');
        $patient6->setIsActive(true);
        $manager->persist($patient6);

        $patient7 = new User();
        $patient7->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P07');
        $patient7->setFirstName('User');
        $patient7->setLastName('One');
        $patient7->setEmail('cst.project5.refresh+test1@gmai.com');
        $patient7->setPassword('$2y$13$ouajAK4OsUqSl47uaz0nwOVDPeQNskHOne8e9Jo.oZfS/4ff1D8.e');
        $patient7->setDate(new \DateTime('1969-11-28'));
        $patient7->setCountry('CA');
        $patient7->setCity('Saskatoon');
        $patient7->setStatus('active');
        $patient7->setIsActive(true);
        $manager->persist($patient7);

        $patient8 = new User();
        $patient8->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P08');
        $patient8->setFirstName('User');
        $patient8->setLastName('One');
        $patient8->setEmail('cst.project5.refresh+test1@gmail.om');
        $patient8->setPassword('$2y$13$ouajAK4OsUqSl47uaz0nwOVDPeQNskHOne8e9Jo.oZfS/4ff1D8.e');
        $patient8->setDate(new \DateTime('1969-11-28'));
        $patient8->setCountry('CA');
        $patient8->setCity('Saskatoon');
        $patient8->setStatus('active');
        $patient8->setIsActive(true);
        $manager->persist($patient8);

        $patient9 = new User();
        $patient9->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P09');
        $patient9->setFirstName('User');
        $patient9->setLastName('One');
        $patient9->setEmail('cst.project5.refresh+test1@gmail.cm');
        $patient9->setPassword('$2y$13$ouajAK4OsUqSl47uaz0nwOVDPeQNskHOne8e9Jo.oZfS/4ff1D8.e');
        $patient9->setDate(new \DateTime('1969-11-28'));
        $patient9->setCountry('CA');
        $patient9->setCity('Saskatoon');
        $patient9->setStatus('active');
        $patient9->setIsActive(true);
        $manager->persist($patient9);

        $patient10 = new User();
        $patient10->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P10');
        $patient10->setFirstName('User');
        $patient10->setLastName('One');
        $patient10->setEmail('cst.project5.refresh+test1@gmail.co');
        $patient10->setPassword('$2y$13$ouajAK4OsUqSl47uaz0nwOVDPeQNskHOne8e9Jo.oZfS/4ff1D8.e');
        $patient10->setDate(new \DateTime('1969-11-28'));
        $patient10->setCountry('CA');
        $patient10->setCity('Saskatoon');
        $patient10->setStatus('active');
        $patient10->setIsActive(true);
        $manager->persist($patient10);

        #endregion
        if (empty($manager->getRepository(User::class)->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9P11'))))
        {
            $patient11 = new User();
            $patient11->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9P11');
            $patient11->setFirstName('User');
            $patient11->setLastName('One');
            $patient11->setEmail('cst.project5.refesh+test1@gmail.co');
            $patient11->setPassword('$2y$13$ouajAK4OsUqSl47uaznwOVDPeQNskHOne8e9Jo.oZfS/4ff1D8.e');
            $patient11->setDate(new \DateTime('1969-11-28'));
            $patient11->setCountry('CA');
            $patient11->setCity('Saskatoon');
            $patient11->setStatus('inactive');
            $patient11->setIsActive(false);
            $manager->persist($patient11);

        $loginUser1 = new User();
        $loginUser1->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9BC7');
        $loginUser1->setFirstName('User');
        $loginUser1->setLastName('One');
        $loginUser1->setEmail('cst.project5.refresh+test1@gmail.com');
        $loginUser1->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
        $loginUser1->setDate(new \DateTime('1969-11-28'));
        $loginUser1->setCountry('CA');
        $loginUser1->setCity('Saskatoon');
        $loginUser1->setStatus('active');
        $loginUser1->setIsActive(true);
        $manager->persist($loginUser1);

            $manager->flush();
        }
    }

    public function unloadWP(ObjectManager $manager)
    {
        for ($i = 1; $i <= 16; $i++)
        {
        	$user = $manager->getRepository(User::class)->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9B' . ($i < 10 ? '0' : '') . $i ));

            if (!empty($user))
            {
                $manager->remove($user);
            }
            $wp = $manager->getRepository(WellnessProfessional::class)->findOneBy(array('user' => '1FAC2763-9FC0-FC21-4762-42330CEB9B' . ($i < 10 ? '0' : '') . $i ));

            if (!empty($wp))
            {
                $manager->remove($wp);
            }
        }

        $manager->flush();
    }

    public function unload(ObjectManager $manager)
    {
        $user1 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7'));

        if (!empty($user1))
        {
            $manager->remove($user1);
        }


        $user2 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6'));

        if (!empty($user2))
        {
            $manager->remove($user2);
        }
        $resp1 = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6'));


        if (!empty($resp1))
        {
            $manager->remove($resp1);
        }

        $user3 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC8'));

        if (!empty($user3))
        {
            $manager->remove($user3);
        }


        $user4 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC9'));

        if (!empty($user4))
        {
            $manager->remove($user4);
        }



        $manager->flush();
    }

    public function unloadGroup(ObjectManager $manager)
    {
        $group1 = $manager->getRepository(Group::class)
            ->findOneBy(array('groupID' => '1'));

        if (!empty($group1))
        {
            $manager->remove($group1);
        }

        $manager->flush();
    }

    public function removePatient(ObjectManager $manager)
    {
        //Needed to use a dql delete to remove entities from the database

        //removes all relationships in the relationship table
        $q = $manager->createQuery('delete from AppBundle\Entity\Relationship r');
        $q->execute();

        //removes all Wellness Professionals in the Wellness Professionals table table
        $q = $manager->createQuery('delete from AppBundle\Entity\WellnessProfessional w');
        $q->execute();

        //removes all Users in the user table.
        $q = $manager->createQuery('delete from AppBundle\Entity\User u');
        $q->execute();
    }

    public function unloadClients(ObjectManager $manager)
    {
        for ($i = 1; $i <= 11; $i++)
        {
        	$user = $manager->getRepository(User::class)->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9P' . ($i < 10 ? '0' : '') . $i ));


            if (!empty($user))
            {
                $manager->remove($user);
            }
        }
        $user = $manager->getRepository(User::class)->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7'));

        if (!empty($user))
        {
            $manager->remove($user);
        }

        $manager->flush();
    }
}