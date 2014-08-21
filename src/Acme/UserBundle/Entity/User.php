<?php
// src/Acme/UserBundle/Entity/User.php

namespace Acme\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    const SCORE = 1;    
    /**
     * 
     * @ORM\Column(type="integer", length=11)
     *
     */
    protected $petition  = self::SCORE;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->roles = array('ROLE_USER');
        $this->petition = 0;
    }

    /**
     * Sets the email.
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        parent::setUsername($email);

        return parent::setEmail($email);
    }

    /**
     * Set the canonical email.
     *
     * @param string $emailCanonical
     * @return User
     */
    public function setEmailCanonical($emailCanonical)
    {
        parent::setUsernameCanonical($emailCanonical);

        return parent::setEmailCanonical($emailCanonical);
    }
    
    /**
     * 
     * @ORM\Column(type="string", length=255)
     *
     */
    protected $name;

    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    
    public function getPetition()
    {
        return $this->petition;
    }
    
    public function setPetition($petition)
    {
        $this->petition = $petition;

        return $this;
    }
    
    //represent object as string
    /*public function __toString()
    {
        return (string) $this->getUsername();
    }*/  
}

