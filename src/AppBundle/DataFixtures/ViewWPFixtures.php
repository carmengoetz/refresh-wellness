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

class ViewWPFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Create these users for the functional tests

        if (empty($manager->getRepository(User::class)->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEVWPC7'))))
        {
            $user1 = new User();
            $user1->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWPC7');
            $user1->setFirstName('User');
            $user1->setLastName('One');
            $user1->setEmail('viewWellnessPro@userone.com');
            $user1->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user1->setDate(new \DateTime('1969-11-28'));
            $user1->setCountry('CA');
            $user1->setCity('Saskatoon');
            $user1->setStatus('active');
            $user1->setIsActive(true);
            $manager->persist($user1);

            $user2 = new User();
            $user2->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWPC6');
            $user2->setFirstName('User');
            $user2->setLastName('Two');
            $user2->setEmail('viewWellnessPro@usertwo.com');
            $user2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user2->setDate(new \DateTime('1969-11-28'));
            $user2->setCountry('CA');
            $user2->setCity('Calgary');
            $user2->setStatus('active');
            $user2->setIsActive(true);
            $manager->persist($user2);

            $user3 = new User();
            $user3->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWPC8');
            $user3->setFirstName('User');
            $user3->setLastName('Three');
            $user3->setEmail('viewWellnessPro@userthree.com');
            $user3->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user3->setDate(new \DateTime('1969-11-28'));
            $user3->setCountry('CA');
            $user3->setCity('Winnipeg');
            $user3->setStatus('inactive');
            $user3->setIsActive(false);
            $manager->persist($user3);

            $user4 = new User();
            $user4->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWPC9');
            $user4->setFirstName('User');
            $user4->setLastName('Four');
            $user4->setEmail('viewWellnessPro@userfour.com');
            $user4->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user4->setDate(new \DateTime('1969-11-28'));
            $user4->setCountry('CA');
            $user4->setCity('Winnipeg');
            $user4->setStatus('active');
            $user4->setIsActive(false);
            $manager->persist($user4);

            ///******************** ADD FIXTURES FOR WELLNESS QUESTIONS */
            //$resp1 = new Respondent();
            //$resp1->setUser($user2);
            //$resp1->setAtRisk(false);
            //$resp1->setMentalState('gud');
            //$resp1->setStressLevel(5);
            //$resp1->setDoctor("Dr. Feelgud");
            //$resp1->setEmergencyContact('8675309');

            //$manager->persist($resp1);

            $user5 = new User();
            $user5->setUserId('2FAC2763-9FC0-FC21-4762-42330CEVWPC5');
            $user5->setFirstName('User');
            $user5->setLastName('Three');
            $user5->setEmail('imnottigerwoods2@yahoo.ca');
            $user5->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user5->setDate(new \DateTime('1969-11-28'));
            $user5->setCountry('CA');
            $user5->setCity('Saskatoon');
            $user5->setStatus('active');
            $user5->setIsActive(true);
            $manager->persist($user5);

            /* Updates for wellness pro relationship */
            //Add caregiver relationship
            $relationship = new Relationship();
            $relationship->setUserIdOne($user1);
            $relationship->setUserIdTwo($user2);
            $relationship->setType("supporter");
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
            $relationship2->setType("wellnessprofessional");
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
            $respondent2->setUser($user5);
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



            $manager->flush();
        }

    }

    public function loadWP(ObjectManager $manager)
    {
        if (empty($manager->getRepository(User::class)->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9B01'))))
        {
            $user1 = new User();
            $user1->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP01');
            $user1->setFirstName('Bob');
            $user1->setLastName('Bob');
            $user1->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user1->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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
            $user2->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP02');
            $user2->setFirstName('Clara');
            $user2->setLastName('Clara');
            $user2->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user2->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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
            $user3->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP03');
            $user3->setFirstName('Carmen');
            $user3->setLastName('Goetz');
            $user3->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user3->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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
            $user4->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP04');
            $user4->setFirstName('Graham');
            $user4->setLastName('Pyett');
            $user4->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user4->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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
            $user5->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP05');
            $user5->setFirstName('Abigail');
            $user5->setLastName('Williamson');
            $user5->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user5->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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
            $user6->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP06');
            $user6->setFirstName('Draden');
            $user6->setLastName('Sawkey');
            $user6->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user6->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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
            $user7->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP07');
            $user7->setFirstName('Chrisp');
            $user7->setLastName('Chrisp');
            $user7->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user7->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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
            $user8->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP08');
            $user8->setFirstName('Graham');
            $user8->setLastName('Saufert');
            $user8->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user8->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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
            $user9->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP09');
            $user9->setFirstName('Wade');
            $user9->setLastName('Lahoda');
            $user9->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user9->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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
            $user10->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP10');
            $user10->setFirstName('Ernesto');
            $user10->setLastName('Basoalto');
            $user10->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user10->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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
            $user11->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP11');
            $user11->setFirstName('Rick');
            $user11->setLastName('Caron');
            $user11->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user11->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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
            $user12->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP12');
            $user12->setFirstName('Ron');
            $user12->setLastName('New');
            $user12->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user12->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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
            $user13->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP13');
            $user13->setFirstName('Ben');
            $user13->setLastName('Benson');
            $user13->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user13->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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
            $user14->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP14');
            $user14->setFirstName('Shane');
            $user14->setLastName('McDonald');
            $user14->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user14->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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
            $user15->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP15');
            $user15->setFirstName('Sharon');
            $user15->setLastName('McDonals');
            $user15->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user15->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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
            $user16->setUserId('1FAC2763-9FC0-FC21-4762-42330CEVWP16');
            $user16->setFirstName('Fred');
            $user16->setLastName('Flintstone');
            $user16->setEmail('cst.project5.refresh+'.uniqid(). '@gmail.com');
            $user16->setPassword('$2y$13$.c4Bfikz6GK1E7LLMyVF/.JngkyquqCHHcgcIxlbKOTf3SQSiEpe.');
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

    public function unloadWP(ObjectManager $manager)
    {
        for ($i = 1; $i <= 16; $i++)
        {
            $wp = $manager->getRepository(WellnessProfessional::class)->findOneBy(array('user' => '1FAC2763-9FC0-FC21-4762-42330CEVWP' . ($i < 10 ? '0' : '') . $i ));

            if (!empty($wp)){
                $manager->remove($wp);
                $manager->remove($wp->getUserId());
            }

        }

        $manager->flush();
    }

    public function unload(ObjectManager $manager)
    {
        $user1 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEVWPC7'));

        if (!empty($user1))
        {
            $manager->remove($user1);
        }


        $user2 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEVWPC6'));

        if (!empty($user2))
        {
            $manager->remove($user2);
        }
        $resp1 = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '1FAC2763-9FC0-FC21-4762-42330CEVWPC6'));


        if (!empty($resp1))
        {
            $manager->remove($resp1);
        }

        $user3 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEVWPC8'));

        if (!empty($user3))
        {
            $manager->remove($user3);
        }


        $user4 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEVWPC9'));

        if (!empty($user4))
        {
            $manager->remove($user4);
        }

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
            ->findOneBy(array('relationshipId' => '1FAC2763-9FC0-FC21-4762-42330CEVWPC6:1FAC2763-9FC0-FC21-4762-42330CEVWPC7:wellnessprofessional'));
        if(!empty($relationship))
        {
            $manager->remove($relationship);
        }

        //Remove relationship
        $relationship = $manager->getRepository(Relationship::class)
            ->findOneBy(array('relationshipId' => '1FAC2763-9FC0-FC21-4762-42330CEVWPC7:1FAC2763-9FC0-FC21-4762-42330CEVWPC6:supporter'));
        if(!empty($relationship))
        {
            $manager->remove($relationship);
        }

        //Remove Respondent for user 2
        $respondentOne = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '2FAC2763-9FC0-FC21-4762-42330CEVWPC6'));

        if (!empty($respondentOne))
        {
        	$manager->remove($respondentOne);
        }


        //Remove Respondent for user 3
        $respondentTwo = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '2FAC2763-9FC0-FC21-4762-42330CEVWPC5'));

        if (!empty($respondentTwo))
        {
        	$manager->remove($respondentTwo);
        }

        //Remove wellness professional
        $wp = $manager->getRepository(WellnessProfessional::class)
            ->findOneBy(array('user'=>'1FAC2763-9FC0-FC21-4762-42330CEVWPC7'));

        if(!empty($wp))
        {
            $manager->remove($wp);
        }

        //Remove user1
        $user1 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '2FAC2763-9FC0-FC21-4762-42330CEVWPC7'));

        if(!empty($user1))
        {
            $manager->remove($user1);
        }

        //Remove user2
        $user2 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '2FAC2763-9FC0-FC21-4762-42330CEVWPC6'));

        if(!empty($user2))
        {
            $manager->remove($user2);
        }

        //Remove user3
        $user3 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '2FAC2763-9FC0-FC21-4762-42330CEVWPC5'));

        if(!empty($user3))
        {
            $manager->remove($user3);
        }

        $manager->flush();
    }

}