<?php
// src/Algo/SiteBundle/Entity/Role.php

namespace Algo\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="roles")
 */

class Role
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="string", unique=true)
	 */
	protected $description;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $hierarchy;

	/**
	 * @ORM\OneToMany(targetEntity="Document", mappedBy="role")
	 */
	protected $documents;
	
	public function __construct()
	{
		$this->documents= new ArrayCollection();
	}

    /**
     * Set description
     *
     * @param string $description
     * @return Role
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
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
     * Set hierarchy
     *
     * @param integer $hierarchy
     * @return Role
     */
    public function setHierarchy($hierarchy)
    {
        $this->hierarchy = $hierarchy;
    
        return $this;
    }

    /**
     * Get hierarchy
     *
     * @return integer 
     */
    public function getHierarchy()
    {
        return $this->hierarchy;
    }

    /**
     * Add documents
     *
     * @param \Algo\SiteBundle\Entity\Document $documents
     * @return Role
     */
    public function addDocument(\Algo\SiteBundle\Entity\Document $documents)
    {
        $this->documents[] = $documents;
    
        return $this;
    }

    /**
     * Remove documents
     *
     * @param \Algo\SiteBundle\Entity\Document $documents
     */
    public function removeDocument(\Algo\SiteBundle\Entity\Document $documents)
    {
        $this->documents->removeElement($documents);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocuments()
    {
        return $this->documents;
    }
}