<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use AppBundle\Entity\Relationship;
use AppBundle\Entity\Message;
use AppBundle\Entity\Conversation;
use AppBundle\Entity\WellnessProfessional;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 *
 *
 * @version 1.0
 * @author cst213
 */
class Messaging
{
    private $em;
    private $validator;

    /**
     * Sets the entity manager in the class
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager, ValidatorInterface $validator)
    {
        $this->em = $entityManager;
        $this->validator = $validator;
    }

    /**
     * function to send a message from one user to another, determining if
     * there is the correct relationship type between the two users, and
     * returns if the message sent successfully or not
     * @param mixed $jsonObj - contains sender, receiver, and message content
     */
    public function sendMessage($jsonObj, User $loggedInUser)
    {
        $status = "failure";
        $data = array();
        $message = "";

        //decode the json that hs been passed in the URL params into a php object
        $messageObj = json_decode($jsonObj);

        //creating a message object
        $messageToSend = new Message();

        //set the messagecontent
        $messageToSend->setMessageContent($messageObj->message);

        //Verify that the sender userID matches the user that is logged in
        if($loggedInUser->getUserId() != $messageObj->sender)
        {
            $message .= 'Please provide a sender.';
        }
        else
        {
            //setting message to send to the logged in user
            $messageToSend->setSender($loggedInUser);

            //no receiver in the message
            if(empty($messageObj->receiver))
            {
                $message .= 'Please provide a receiver.';
            }
            else
            {

                //Find receiver user object
                $repo = $this->em->getRepository(User::class);
                $receiver = $repo->findOneBy(['userID' => $messageObj->receiver]);

                //receiver doesn't exist
                if(!isset($receiver))
                {
                    $message .= 'Receiver does not exist.';
                }
                else
                {
                    $messageToSend->setReceiver($receiver);

                    //Find relationship between sender and receiver
                    $repo = $this->em->getRepository(Relationship::class);
                    $relationship = $repo->findBy(['userIdOne'=>$messageToSend->getSender(),
                                                  'userIdTwo'=>$messageToSend->getReceiver()]);
                    if (count($relationship) < 1)
                    {
                    	$relationship = $repo->findBy(['userIdTwo'=>$messageToSend->getSender(),
                                                  'userIdOne'=>$messageToSend->getReceiver()]);
                    }


                    //no relashiship between sender and receiver
                    if (!isset($relationship[0]))
                    {
                        $message .= 'You cannot message this user without a previously set relationship.';
                    }
                    else
                    {
                        //validate the message

                        $errors = $this->validator->validate($messageToSend);

                        //Check to see if there were any errors
                        if(count($errors) > 0)
                        {
                            //If so, add to error message
                            $message .= $errors->get(0)->getMessage();

                        }
                        else
                        {
                            //If not, add message to the database
                            $this->em->persist($messageToSend);
                            $this->em->flush();
                            //Set status to success
                            $status = "success";

                            /***** Add code to create/update conversation table *****/
                            //Check to see if the conversation has been created
                            $repo = $this->em->getRepository(Conversation::class);
                            $existingConvo = $repo->findOneBy(['userOneID'=>$messageToSend->getSender(),
                                                  'userTwoID'=>$messageToSend->getReceiver()]);
                            $existingConvoReverse = $repo->findOneBy(['userOneID'=>$messageToSend->getReceiver(),
                                                  'userTwoID'=>$messageToSend->getSender()]);
                            //If conversation does not exist create it
                            if(!isset($existingConvo) && !isset($existingConvoReverse))
                            {
                                //Initial conversation object
                                $conversation = new Conversation();
                                //setting the initiater as user on
                                $conversation->setUserOneID($messageToSend->getSender());
                                //setting the receiver as user two
                                $conversation->setUserTwoID($messageToSend->getReceiver());
                                //Setting the contents of the last message to that of the message that
                                //created the conversation
                                $conversation->setLastMessage($messageToSend);
                                //adding the conversation to the database.
                                $this->em->persist($conversation);

                            }
                            else
                            {
                                if (isset($existingConvo))
                                {
                                    $existingConvo->setLastMessage($messageToSend);


                                }
                                elseif(isset($existingConvoReverse))
                                    $existingConvoReverse->setLastMessage($messageToSend);


                            }
                            $this->em->flush();
                        }
                    }
                }
            }
        }

        //Set up and return array
        $returnArray = array(
            'status'=>$status,
            'message'=>$message,
            'data'=> $data
            );

        return $returnArray;
    }
}