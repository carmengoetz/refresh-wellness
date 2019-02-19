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
class ViewForMessagesFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Only load fixtures if a user with this id does not already exist
        if(empty($manager->getRepository(User::class)->findOneBy(array('userID' => '5050F3C6-8475-491E-A63D-F9F356F98C81'))))
        {
            /* Make 3 users */

            //This user will have one message
            $user = new User();
            $user->setUserId('5050F3C6-8475-491E-A63D-F9F356F98C81');
            $user->setFirstName('Steve');
            $user->setLastName('Rogers');
            $user->setEmail('messageViewerOne@gmail.com');
            $user->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user->setDate(new \DateTime('1969-11-28'));
            $user->setCountry('CA');
            $user->setCity('Saskatoon');
            $user->setStatus('active');
            $user->setIsActive(true);
            $manager->persist($user);

            //This user sends the message to the first user
            $user2 = new User();
            $user2->setUserId('5050F3C6-8475-491E-A63D-F9F356F98C82');
            $user2->setFirstName('Bruce');
            $user2->setLastName('Banner');
            $user2->setEmail('messageViewerTwo@gmail.com');
            $user2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user2->setDate(new \DateTime('1969-11-28'));
            $user2->setCountry('CA');
            $user2->setCity('Saskatoon');
            $user2->setStatus('active');
            $user2->setIsActive(true);
            $manager->persist($user2);

            //user 2 is a wellness professional
            $wp = new WellnessProfessional();
            $wp->setUser($user2);
            $wp->setPracticeName('Gamma Radiation Therapy');
            $wp->setContactNumber('3061234567');
            $wp->setContactEmail('gammarad@culver.uni.org');
            $manager->persist($wp);

            //This user will have 250 messages
            $user3 = new User();
            $user3->setUserId('50F3C6-8475-491E-A63D-F9F356F98C83');
            $user3->setFirstName('Tony');
            $user3->setLastName('Stark');
            $user3->setEmail('messageViewerThree@gmail.com');
            $user3->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user3->setDate(new \DateTime('1969-11-28'));
            $user3->setCountry('CA');
            $user3->setCity('Saskatoon');
            $user3->setStatus('active');
            $user3->setIsActive(true);
            $manager->persist($user3);

            //User to have 50 many conversations
            $user4 = new User();
            $user4->setUserId('50F3C6-8475-491E-A63D-F9F356F98C84');
            $user4->setFirstName('Fred');
            $user4->setLastName('Anderson');
            $user4->setEmail('messageViewerFour@gmail.com');
            $user4->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user4->setDate(new \DateTime('1969-11-28'));
            $user4->setCountry('CA');
            $user4->setCity('Saskatoon');
            $user4->setStatus('active');
            $user4->setIsActive(true);
            $manager->persist($user4);

            //This user has no messages
            $user5 = new User();
            $user5->setUserId('5050F3C6-8475-491E-A63D-F9F356F98C85');
            $user5->setFirstName('Bruce');
            $user5->setLastName('Wayne');
            $user5->setEmail('messageViewerFive@gmail.com');
            $user5->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user5->setDate(new \DateTime('1969-11-28'));
            $user5->setCountry('CA');
            $user5->setCity('Saskatoon');
            $user5->setStatus('active');
            $user5->setIsActive(true);
            $manager->persist($user5);

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


            //create messages

            //1 message for user1
            $msgUser1 = new Message();
            $msgUser1->setSender($user2);
            $msgUser1->setReceiver($user);
            $msgUser1->setDateSent(new \DateTime('2015-05-01'));
            $msgUser1->setTimeSent(new \DateTime("4:00 pm"));
            $msgUser1->setMessageContent("Hulk go off world. bye");
            $msgUser1->setIsRead(true);
            $manager->persist($msgUser1);

            $msg1Convo = new Conversation();
            $msg1Convo->setUserOneID($user2);
            $msg1Convo->setUserTwoID($user);
            $msg1Convo->setLastMessage($msgUser1);
            $manager->persist($msg1Convo);


            //no messages for user2
            #region Old Messages
            /*
            //25 messages for user3 ------ WHY???????????
            $msg1User3 = new Message();
            $msg1User3->setSender($user);
            $msg1User3->setReceiver($user3);
            $msg1User3->setDate(new \DateTime('2016-05-06'));
            $msg1User3->setTime(new \DateTime("4:00 pm"));
            $msg1User3->setMessageContent("Hey Tony I'm bringing Bucky home. Don't kill him");
            $msg1User3->setRead(true);
            $manager->persist($msg1User3);


            $msg2User3 = new Message();
            $msg2User3->setSender($user2);
            $msg2User3->setReceiver($user3);
            $msg2User3->setDate(new \DateTime('2015-05-01'));
            $msg2User3->setTime(new \DateTime("4:00 pm"));
            $msg2User3->setMessageContent("Hulk go off world. bye");
            $msg2User3->setRead(true);
            $manager->persist($msg2User3);

            $msg3User3 = new Message();
            $msg3User3->setSender($user);
            $msg3User3->setReceiver($user3);
            $msg3User3->setDate(new \DateTime('2016-05-06'));
            $msg3User3->setTime(new \DateTime("4:00 pm"));
            $msg3User3->setMessageContent("Another warning. Don't kill him.");
            $msg3User3->setRead(true);
            $manager->persist($msg3User3);

            $msg4User3 = new Message();
            $msg4User3->setSender($user);
            $msg4User3->setReceiver($user3);
            $msg4User3->setDate(new \DateTime('2016-05-07'));
            $msg4User3->setTime(new \DateTime("4:00 pm"));
            $msg4User3->setMessageContent("Yeah I'm gonna go into hiding now byeeeee");
            $msg4User3->setRead(true);
            $manager->persist($msg4User3);

            $msg5User3 = new Message();
            $msg5User3->setSender($user);
            $msg5User3->setReceiver($user3);
            $msg5User3->setDate(new \DateTime('2016-07-08'));
            $msg5User3->setTime(new \DateTime("4:00 pm"));
            $msg5User3->setMessageContent("Hey Tony I'm bout to do a mission. Bucky on ice");
            $msg5User3->setRead(true);
            $manager->persist($msg5User3);

            $msg6User3 = new Message();
            $msg6User3->setSender($user);
            $msg6User3->setReceiver($user3);
            $msg6User3->setDate(new \DateTime('2016-10-31'));
            $msg6User3->setTime(new \DateTime("4:00 pm"));
            $msg6User3->setMessageContent("Happy Halloween. I'm in disguise today. Cap");
            $msg6User3->setRead(true);
            $manager->persist($msg6User3);

            $msg7User3 = new Message();
            $msg7User3->setSender($user);
            $msg7User3->setReceiver($user3);
            $msg7User3->setDate(new \DateTime('2016-10-31'));
            $msg7User3->setTime(new \DateTime("4:00 pm"));
            $msg7User3->setMessageContent("Iasdfasdfasdfsd");
            $msg7User3->setRead(true);
            $manager->persist($msg7User3);

            $msg8User3 = new Message();
            $msg8User3->setSender($user);
            $msg8User3->setReceiver($user3);
            $msg8User3->setDate(new \DateTime('2016-11-01'));
            $msg8User3->setTime(new \DateTime("4:00 pm"));
            $msg8User3->setMessageContent("Sorry I pocket-emailed you.");
            $msg8User3->setRead(true);
            $manager->persist($msg8User3);

            $msg9User3 = new Message();
            $msg9User3->setSender($user);
            $msg9User3->setReceiver($user3);
            $msg9User3->setDate(new \DateTime('2016-12-25'));
            $msg9User3->setTime(new \DateTime("4:00 pm"));
            $msg9User3->setMessageContent("Merry Christmas. Tell Rhodey that I'm glad he's feeling better");
            $msg9User3->setRead(true);
            $manager->persist($msg9User3);

            $msg10User3 = new Message();
            $msg10User3->setSender($user);
            $msg10User3->setReceiver($user3);
            $msg10User3->setDate(new \DateTime('2017-01-01'));
            $msg10User3->setTime(new \DateTime("4:00 pm"));
            $msg10User3->setMessageContent("Hey Tony Happy New Year");
            $msg10User3->setRead(true);
            $manager->persist($msg10User3);

            $msg12User3 = new Message();
            $msg12User3->setSender($user);
            $msg12User3->setReceiver($user3);
            $msg12User3->setDate(new \DateTime('2017-03-06'));
            $msg12User3->setTime(new \DateTime("4:00 pm"));
            $msg12User3->setMessageContent("why do you never return my emails?");
            $msg12User3->setRead(true);
            $manager->persist($msg12User3);

            $msg13User3 = new Message();
            $msg13User3->setSender($user);
            $msg13User3->setReceiver($user3);
            $msg13User3->setDate(new \DateTime('2017-04-13'));
            $msg13User3->setTime(new \DateTime("4:00 pm"));
            $msg13User3->setMessageContent("Tony you really need to reply this time, I've got a mission and might need backup");
            $msg13User3->setRead(true);
            $manager->persist($msg13User3);

            $msg14User3 = new Message();
            $msg14User3->setSender($user);
            $msg14User3->setReceiver($user3);
            $msg14User3->setDate(new \DateTime('2017-05-06'));
            $msg14User3->setTime(new \DateTime("4:00 pm"));
            $msg14User3->setMessageContent("Been a while. Bucky still in ice. I'm still on the run.");
            $msg14User3->setRead(true);
            $manager->persist($msg14User3);

            $msg15User3 = new Message();
            $msg15User3->setSender($user);
            $msg15User3->setReceiver($user3);
            $msg15User3->setDate(new \DateTime('2017-06-17'));
            $msg15User3->setTime(new \DateTime("4:00 pm"));
            $msg15User3->setMessageContent("Do you even caaaaaaaare Tony");
            $msg15User3->setRead(true);
            $manager->persist($msg15User3);

            $msg16User3 = new Message();
            $msg16User3->setSender($user);
            $msg16User3->setReceiver($user3);
            $msg16User3->setDate(new \DateTime('2017-07-04'));
            $msg16User3->setTime(new \DateTime("4:00 pm"));
            $msg16User3->setMessageContent("AMERICAAAAAAAAAAAAAA. it's my birthday too. send me a present. if you can find me");
            $msg16User3->setRead(true);
            $manager->persist($msg16User3);

            $msg17User3 = new Message();
            $msg17User3->setSender($user);
            $msg17User3->setReceiver($user3);
            $msg17User3->setDate(new \DateTime('2017-07-07'));
            $msg17User3->setTime(new \DateTime("4:00 pm"));
            $msg17User3->setMessageContent("your spider kid is on tv");
            $msg17User3->setRead(true);
            $manager->persist($msg17User3);

            $msg18User3 = new Message();
            $msg18User3->setSender($user);
            $msg18User3->setReceiver($user3);
            $msg18User3->setDate(new \DateTime('2017-07-08'));
            $msg18User3->setTime(new \DateTime("4:00 pm"));
            $msg18User3->setMessageContent("Tony why is your spider punk in DC?");
            $msg18User3->setRead(true);
            $manager->persist($msg18User3);

            $msg19User3 = new Message();
            $msg19User3->setSender($user);
            $msg19User3->setReceiver($user3);
            $msg19User3->setDate(new \DateTime('2017-07-09'));
            $msg19User3->setTime(new \DateTime("4:00 pm"));
            $msg19User3->setMessageContent("Tony. your spider boy just took down a villan. where were you? he could have been killed");
            $msg19User3->setRead(true);
            $manager->persist($msg19User3);

            $msg20User3 = new Message();
            $msg20User3->setSender($user);
            $msg20User3->setReceiver($user3);
            $msg20User3->setDate(new \DateTime('2017-08-12'));
            $msg20User3->setTime(new \DateTime("4:00 pm"));
            $msg20User3->setMessageContent("Saw you on tv again. Try actually answering q's next time");
            $msg20User3->setRead(true);
            $manager->persist($msg20User3);

            $msg21User3 = new Message();
            $msg21User3->setSender($user);
            $msg21User3->setReceiver($user3);
            $msg21User3->setDate(new \DateTime('2017-08-30'));
            $msg21User3->setTime(new \DateTime("4:00 pm"));
            $msg21User3->setMessageContent("Going underground now");
            $msg21User3->setRead(true);
            $manager->persist($msg21User3);

            $msg22User3 = new Message();
            $msg22User3->setSender($user);
            $msg22User3->setReceiver($user3);
            $msg22User3->setDate(new \DateTime('2018-01-01'));
            $msg22User3->setTime(new \DateTime("4:00 pm"));
            $msg22User3->setMessageContent("Happy new year again");
            $msg22User3->setRead(true);
            $manager->persist($msg22User3);

            $msg23User3 = new Message();
            $msg23User3->setSender($user2);
            $msg23User3->setReceiver($user3);
            $msg23User3->setDate(new \DateTime('2018-01-01'));
            $msg23User3->setTime(new \DateTime("4:00 pm"));
            $msg23User3->setMessageContent("Hey I'm banner again and back on Earth. Long story. Happy New Year");
            $msg23User3->setRead(true);
            $manager->persist($msg23User3);

            $msg24User3 = new Message();
            $msg24User3->setSender($user);
            $msg24User3->setReceiver($user3);
            $msg24User3->setDate(new \DateTime('2018-02-16'));
            $msg24User3->setTime(new \DateTime("4:00 pm"));
            $msg24User3->setMessageContent("Hey you hear about Wakanda? Crazy");
            $msg24User3->setRead(true);
            $manager->persist($msg24User3);

            $msg25User3 = new Message();
            $msg25User3->setSender($user);
            $msg25User3->setReceiver($user3);
            $msg25User3->setDate(new \DateTime('2018-03-05'));
            $msg25User3->setTime(new \DateTime("4:00 pm"));
            $msg25User3->setMessageContent("See you in May. Cap");
            $msg25User3->setRead(false);
            $manager->persist($msg25User3);

             */
            #endregion

            //Variable to hold the final message of the conversation once the loop ends
            $messageForDBFromLoop = NULL;
            //Loop to have many messages in a single conversation
            for($i = 1; $i <= 250; $i++)
            {
                if($i%2 == 0)
                {
                    $evenMsgLoop = new Message();
                    $evenMsgLoop->setSender($user);
                    $evenMsgLoop->setReceiver($user3);
                    if($i < 10)
                    {
                        $evenMsgLoop->setDateSent(new \DateTime('200'.$i.'-02-05'));
                    }
                    elseif ($i < 100 && $i >= 10)
                    {
                        $evenMsgLoop->setDateSent(new \DateTime('20'.$i.'-02-05'));
                    }
                    else
                    {
                        $evenMsgLoop->setDateSent(new \DateTime('2'.$i.'-02-05'));
                    }

                    $evenMsgLoop->setTimeSent(new \DateTime("4:00 pm"));
                    $evenMsgLoop->setMessageContent("Don't sell my shield while I'm gone");
                    $evenMsgLoop->setIsRead(true);
                    $manager->persist($evenMsgLoop);
                    $messageForDBFromLoop = $evenMsgLoop;
                }
                else
                {
                    $oddMsgLoop = new Message();
                    $oddMsgLoop->setReceiver($user);
                    $oddMsgLoop->setSender($user3);
                    if($i < 10)
                    {
                        $oddMsgLoop->setDateSent(new \DateTime('200'.$i.'-02-05'));
                    }
                    else if ($i < 100 && $i >= 10)
                    {
                        $oddMsgLoop->setDateSent(new \DateTime('20'.$i.'-02-05'));
                    }
                    else
                    {
                        $oddMsgLoop->setDateSent(new \DateTime('2'.$i.'-02-05'));
                    }
                    $oddMsgLoop->setTimeSent(new \DateTime("4:00 pm"));
                    $oddMsgLoop->setMessageContent("I do what I want when I want");
                    $oddMsgLoop->setIsRead(true);
                    $manager->persist($oddMsgLoop);
                    $messageForDBFromLoop = $oddMsgLoop;
                }
            }

            $messageLoop = new Conversation();
            $messageLoop->setUserOneID($user);
            $messageLoop->setUserTwoID($user3);
            $messageLoop->setLastMessage($messageForDBFromLoop);
            $manager->persist($messageLoop);


            //Loop to create many users to test loading many conversations
            for($i = 0; $i < 50; $i++)
            {
                if($i< 10)
                {
                    $userLoop = new User();
                    $userLoop->setUserId('50F3C6-8475-491E-A63D-F9F356F98C0' . $i);
                    $userLoop->setFirstName('Clony');
                    $userLoop->setLastName('Stark');
                    $userLoop->setEmail('messageConversation'.$i.'@gmail.com');
                    $userLoop->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
                    $userLoop->setDate(new \DateTime('19'.$i.'-11-28'));
                    $userLoop->setCountry('CA');
                    $userLoop->setCity('Saskatoon');
                    $userLoop->setStatus('active');
                    $userLoop->setIsActive(true);
                    $manager->persist($userLoop);
                }
                else
                {
                    $userLoop = new User();
                    $userLoop->setUserId('50F3C6-8475-491E-A63D-F9F356F98C' . $i);
                    $userLoop->setFirstName('Clony');
                    $userLoop->setLastName('Stark');
                    $userLoop->setEmail('messageConversation'.$i.'@gmail.com');
                    $userLoop->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
                    $userLoop->setDate(new \DateTime('19'.$i.'-11-28'));
                    $userLoop->setCountry('CA');
                    $userLoop->setCity('Saskatoon');
                    $userLoop->setStatus('active');
                    $userLoop->setIsActive(true);
                    $manager->persist($userLoop);
                }

                $rel = new Relationship();
                $rel->setUserIdOne($user4);
                $rel->setUserIdTwo($userLoop);
                $rel->setType("friend");
                $rel->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
                $rel->setStatus("active");
                $rel->setRelationshipId();
                $manager->persist($rel);
                if($i <10)
                {
                    $msgUserLoop = new Message();
                    $msgUserLoop->setSender($userLoop);
                    $msgUserLoop->setReceiver($user4);
                    $msgUserLoop->setDateSent(new \DateTime('200'.$i.'-02-05'));
                    $msgUserLoop->setTimeSent(new \DateTime("4:00 pm"));
                    $msgUserLoop->setMessageContent("I am the real Tony Stark");
                    $msgUserLoop->setIsRead(false);
                    $manager->persist($msgUserLoop);
                }
                else
                {
                    $msgUserLoop = new Message();
                    $msgUserLoop->setSender($userLoop);
                    $msgUserLoop->setReceiver($user4);
                    $msgUserLoop->setDateSent(new \DateTime('20'.$i.'-02-05'));
                    $msgUserLoop->setTimeSent(new \DateTime("4:00 pm"));
                    $msgUserLoop->setMessageContent("I am the real Tony Stark");
                    $msgUserLoop->setIsRead(false);
                    $manager->persist($msgUserLoop);
                }


                $conversationLoop = new Conversation();
                $conversationLoop->setUserOneID($user4);
                $conversationLoop->setUserTwoID($userLoop);
                $conversationLoop->setLastMessage($msgUserLoop);
                $manager->persist($conversationLoop);

            }

            $manager->flush();
        }
    }

    public function unload(ObjectManager $manager)
    {
        //Remove conversations
        for($i = 1; $i <= 53; $i++)
        {
            $convoToRemove = $manager->getRepository(Conversation::class)
            ->findOneBy((array('id'=>$i)));
            if (!empty($convoToRemove))
            {
                $manager->remove($convoToRemove);
            }
        }

        //Removing messages
        for($i = 1; $i <= 301; $i++)
        {
            $msgToRemove = $manager->getRepository(Message::class)
            ->findOneBy((array('messageID'=>$i)));
            if (!empty($msgToRemove))
            {
                $manager->remove($msgToRemove);
            }
        }

        //remove relationship
        $rel = $manager->getRepository(Relationship::class)
            ->findOneBy((array('relationshipId'=>'5050F3C6-8475-491E-A63D-F9F356F98C81:5050F3C6-8475-491E-A63D-F9F356F98C82:support')));
        if (!empty($rel))
        {
        	$manager->remove($rel);
        }

        //remove relationship2
        $rel2 = $manager->getRepository(Relationship::class)
            ->findOneBy((array('relationshipId'=>'5050F3C6-8475-491E-A63D-F9F356F98C81:50F3C6-8475-491E-A63D-F9F356F98C83:friend')));
        if (!empty($rel2))
        {
        	$manager->remove($rel2);
        }

        //remove relationship3
        $rel3 = $manager->getRepository(Relationship::class)
            ->findOneBy((array('relationshipId'=>'5050F3C6-8475-491E-A63D-F9F356F98C82:50F3C6-8475-491E-A63D-F9F356F98C83:client')));
        if (!empty($rel3))
        {
        	$manager->remove($rel3);
        }

        //Remove relationships for user4 for with loop
        for($i = 0; $i < 50; $i++)
        {
            if($i< 10)
            {
                $rel3 = $manager->getRepository(Relationship::class)
                    ->findOneBy((array('relationshipId'=>'50F3C6-8475-491E-A63D-F9F356F98C84:50F3C6-8475-491E-A63D-F9F356F98C0' . $i . ':friend')));
                if (!empty($rel3))
                {
                    $manager->remove($rel3);
                }

                //Remove the user
                $user = $manager->getRepository(User::class)
                    ->findOneBy((array('userID'=>'50F3C6-8475-491E-A63D-F9F356F98C0' . $i )));
                if (!empty($user))
                {
                    $manager->remove($user);
                }
            }
            else
            {
                $rel3 = $manager->getRepository(Relationship::class)
                    ->findOneBy((array('relationshipId'=>'50F3C6-8475-491E-A63D-F9F356F98C84:50F3C6-8475-491E-A63D-F9F356F98C' . $i . ':friend')));
                if (!empty($rel3))
                {
                    $manager->remove($rel3);
                }

                //Remove the user
                $user = $manager->getRepository(User::class)
                    ->findOneBy((array('userID'=>'50F3C6-8475-491E-A63D-F9F356F98C' . $i )));
                if (!empty($user))
                {
                    $manager->remove($user);
                }
            }

        }

        //remove wellness professional
        $wp = $manager->getRepository(WellnessProfessional::class)
            ->findOneBy((array('user'=>'5050F3C6-8475-491E-A63D-F9F356F98C82')));
        if (!empty($wp))
        {
        	$manager->remove($wp);
        }

        //remove first user
        $user = $manager->getRepository(User::class)
            ->findOneBy((array('userID'=>'5050F3C6-8475-491E-A63D-F9F356F98C81')));
        if (!empty($user))
        {
        	$manager->remove($user);
        }

        //remove user2
        $user2 = $manager->getRepository(User::class)
            ->findOneBy((array('userID'=>'5050F3C6-8475-491E-A63D-F9F356F98C82')));
        if (!empty($user2))
        {
        	$manager->remove($user2);
        }

        //remove user3
        $user3 = $manager->getRepository(User::class)
            ->findOneBy((array('userID'=>'50F3C6-8475-491E-A63D-F9F356F98C83')));
        if (!empty($user3))
        {
        	$manager->remove($user3);
        }

        //remove user4
        $user4 = $manager->getRepository(User::class)
            ->findOneBy((array('userID'=>'50F3C6-8475-491E-A63D-F9F356F98C84')));
        if (!empty($user4))
        {
        	$manager->remove($user4);
        }

        //remove user5
        $user4 = $manager->getRepository(User::class)
            ->findOneBy((array('userID'=>'5050F3C6-8475-491E-A63D-F9F356F98C85')));
        if (!empty($user4))
        {
        	$manager->remove($user4);
        }

        $manager->flush();

    }
}