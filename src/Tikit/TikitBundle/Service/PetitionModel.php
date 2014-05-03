<?php
// Service Code
namespace Tikit\TikitBundle\Service;

use Doctrine\ORM\Mapping as ORM;
use Tikit\TikitBundle\Entity\Petition;
use Tikit\TikitBundle\Entity\PetitionScore;
use Tikit\TikitBundle\Entity\SiteSpam;
use Tikit\TikitBundle\Entity\SpamUserCount;

use Doctrine\ORM\Query\ResultSetMapping;

class PetitionModel
{
//
    const LINK_ATTACH_TYPE            = 'link';
    const VIDEO_ATTACH_TYPE           = 'video';


    protected $em;

    public function __construct($entityManager)
    {
        $this->em = $entityManager;
    }

    public function votePetition($petition_id,$user_id,$vote)
    {
        $petitionscore = $this->em->getRepository('\Tikit\TikitBundle\Entity\PetitionScore')->findOneBy(array('user' => $user_id, 'petition' => $petition_id));
        if($petitionscore){
            if($petitionscore->getVote() == $vote)
                return 0;
            $petitionscore->setVote($vote);
            if ($vote == -1) {
                $vote = -2;
            } elseif ($vote == 1) {
                $vote = 2;
            }
        } else {
            $petitionscore = new PetitionScore($petition_id,$user_id,$vote);
        }
        $petition = $this->em->find('\Tikit\TikitBundle\Entity\Petition', $petition_id);
        $petition->setScore($petition->getScore() + $vote);
        $petitionscore->setPetition($petition);
        $user = $this->em->find('\Tikit\TikitBundle\Entity\FosUser', $user_id);
        $petitionscore->setUser($user);
        $this->em->persist($petitionscore);
        $this->em->persist($petition);
        $this->em->flush();
        return 1;
    }

    
}