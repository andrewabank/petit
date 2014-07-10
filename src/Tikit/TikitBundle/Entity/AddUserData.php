<?php

namespace Tikit\TikitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AddUserData
 */
class AddUserData
{
    /**
     * @var integer
     */
    private $score;

    /**
     * @var \DateTime
     */
    private $dateSatusUpdated;

    /**
     * 
     * @var integer
    *
     */
    private $id;

    /**
     * @var \Acme\UserBundle\Entity\User
     */
    private $user;

    public function __construct()
    {
        $this->dateSatusUpdated = new \DateTime('now');
    }
    /**
     * Set score
     *
     * @param integer $score
     * @return AddUserData
     */
    public function setScore($score)
    {
        $this->score = $score;
    
        return $this;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set dateSatusUpdated
     *
     * @param \DateTime $dateSatusUpdated
     * @return AddUserData
     */
    public function setDateSatusUpdated($dateSatusUpdated)
    {
        $this->dateSatusUpdated = $dateSatusUpdated;
    
        return $this;
    }

    /**
     * Get dateSatusUpdated
     *
     * @return \DateTime 
     */
    public function getDateSatusUpdated()
    {
        return $this->dateSatusUpdated;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \Acme\UserBundle\Entity\User $user
     * @return AddUserData
     */
    public function setUser(\Acme\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Acme\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
