<?php
// src/Algo/SiteBundle/Entity/User.php

namespace Algo\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

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
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $approved;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected $erpid;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $name;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->setApproved(false);
		$this->setEnabled(false);
		
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
     * Set approved
     *
     * @param boolean $approved
     * @return User
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    
        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean 
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set erpid
     *
     * @param integer $erpid
     * @return User
     */
    public function setErpid($erpid)
    {
        $this->erpid = $erpid;
    
        return $this;
    }

    /**
     * Get erpid
     *
     * @return integer 
     */
    public function getErpid()
    {
        return $this->erpid;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}