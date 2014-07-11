<?php

namespace Tikit\TikitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Petition
 */
class Petition
{
    const STATUS_BLOCKED = 0;
    const STATUS_DISPLAY_PUBLIC = 1;
    
    const SCORE = 1;
    /**
     * @var string
     */
    private $petitionDescription;
    /**
     * @var string
     */
    private $petitionTitle;
    /**
     * @var string
     */
    private $petitionUrl;

    /**
     * @var integer
     */
    private $score = self::SCORE;

    /**
     * @var \DateTime
     */
    private $dateAdded;

    /**
     * @var boolean
     */
    private $status = self::STATUS_DISPLAY_PUBLIC;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Acme\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Tikit\TikitBundle\Entity\Category
     */
    private $category;

    public function __construct()
    {
        $this->dateAdded = new \DateTime('now');
        //$this->user = null;
    }
    /**
     * Set petitionTitle
     *
     * @param string $petitionTitle
     * @return Petition
     */
    public function setPetitionTitle($petitionTitle)
    {
        $this->petitionTitle = $petitionTitle;
    
        return $this;
    }

    /**
     * Get petitionTitle
     *
     * @return string 
     */
    public function getPetitionTitle()
    {
        return $this->petitionTitle;
    }
    /**
     * Set petitionDescription
     *
     * @param string $petitionDescription
     * @return Petition
     */
    public function setPetitionDescription($petitionDescription)
    {
        $this->petitionDescription = $petitionDescription;
    
        return $this;
    }

    /**
     * Get petitionDescription
     *
     * @return string 
     */
    public function getPetitionDescription()
    {
        return $this->petitionDescription;
    }

    /**
     * Set petitionUrl
     *
     * @param string $petitionUrl
     * @return Petition
     */
    public function setPetitionUrl($petitionUrl)
    {
        $this->petitionUrl = $petitionUrl;
    
        return $this;
    }

    /**
     * Get petitionUrl
     *
     * @return string 
     */
    public function getPetitionUrl()
    {
        return $this->petitionUrl;
    }

    /**
     * Set score
     *
     * @param integer $score
     * @return Petition
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
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return Petition
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    
        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime 
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Petition
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
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
     * @return Petition
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
     * Set category
     *
     * @param \Tikit\TikitBundle\Entity\Category $category
     * @return Petition
     */
    public function setCategory(\Tikit\TikitBundle\Entity\Category $category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Tikit\TikitBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
