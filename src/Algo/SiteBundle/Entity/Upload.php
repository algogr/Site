<?php
// src/Algo/SiteBundle/Entity/Upload.php

namespace Algo\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="upload")
 */

class Upload
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $short_descr;
	
	/**
	 * @ORM\Column(type="text")
	 */
	protected $descr;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $acl;
	
	/**
	 * @ORM\Column(type="string", length=100)
	 */
	protected $uploader; 

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
     * Set short_descr
     *
     * @param string $shortDescr
     * @return Upload
     */
    public function setShortDescr($shortDescr)
    {
        $this->short_descr = $shortDescr;
    
        return $this;
    }

    /**
     * Get short_descr
     *
     * @return string 
     */
    public function getShortDescr()
    {
        return $this->short_descr;
    }

    /**
     * Set descr
     *
     * @param string $descr
     * @return Upload
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;
    
        return $this;
    }

    /**
     * Get descr
     *
     * @return string 
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * Set acl
     *
     * @param integer $acl
     * @return Upload
     */
    public function setAcl($acl)
    {
        $this->acl = $acl;
    
        return $this;
    }

    /**
     * Get acl
     *
     * @return integer 
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * Set uploader
     *
     * @param string $uploader
     * @return Upload
     */
    public function setUploader($uploader)
    {
        $this->uploader = $uploader;
    
        return $this;
    }

    /**
     * Get uploader
     *
     * @return string 
     */
    public function getUploader()
    {
        return $this->uploader;
    }
}