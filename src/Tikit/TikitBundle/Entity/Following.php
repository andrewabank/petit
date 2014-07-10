<?php

namespace Tikit\TikitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Following
 */
class Following
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Acme\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Acme\UserBundle\Entity\User
     */
    private $follower;


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
     * @return Following
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

    /**
     * Set follower
     *
     * @param \Acme\UserBundle\Entity\User $follower
     * @return Following
     */
    public function setFollower(\Acme\UserBundle\Entity\User $follower = null)
    {
        $this->follower = $follower;
    
        return $this;
    }

    /**
     * Get follower
     *
     * @return \Acme\UserBundle\Entity\User 
     */
    public function getFollower()
    {
        return $this->follower;
    }
}
