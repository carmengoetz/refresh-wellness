<?php
namespace AppBundle\Security;

use AppBundle\Entity\Wellness;
use AppBundle\Entity\User;
use AppBundle\Entity\Respondent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
/**
 * WellnessVoter short summary.
 *
 * Custom security voter to check for if the Respondent can
 * either skip, or answer wellness questions when they log in.
 *
 * @version 1.0
 * @author cst245
 */
class WellnessVoter extends Voter
{
    const ANSWER = 'answer';
    const SKIP = 'skip';
    private $em;

    /**
     * Summary of __construct
     * @param EntityManager $em 
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Summary of supports
     * @param mixed $attribute 
     * @param mixed $subject 
     * @return boolean
     */
    protected function supports($attribute, $subject = null)
    {
        //only vote on user objects inside this voter
        //if the attribute isn't one we support, return false
        return in_array($attribute, [self::ANSWER, self::SKIP], true);
    }

    /**
     * Summary of voteOnAttribute
     * @param mixed $attribute 
     * @param mixed $subject 
     * @param TokenInterface $token 
     * @return mixed
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User)
        {
            return false;
        }

        switch($attribute)
        {
            case self::ANSWER:
                return $this->canAnswer($user);
            case self::SKIP:
                return $this->canSkip($user);
            default:
                return false;
        }

    }

    /**
     * Summary of canSkip
     * @param User $user 
     * @return boolean
     */
    private function canSkip(User $user)
    {
        $respondent = $this->em->getRepository(Respondent::class)->findOneBy(array('user' => $user));

        if ($respondent == null)
        {
            return true;
        }

        $wellness = $this->em->getRepository(Wellness::class)->findOneBy(array('respondent' => $respondent, 'date' => date('Y-m-d')));

        if ($wellness != null)
        {
            return true;
        }

        return false;


    }

}