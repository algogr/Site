<?php
// src/Algo/SiteBundle/Entity/Document.php

namespace Algo\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Algo\SiteBundle\Entity\Repository\DocumentRepository")
 * @ORM\Table(name="documents")
 */

class Document
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
	protected $filename;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $shortdescr;
	
	/**
	 * @ORM\Column(type="text")
	 */
	protected $descr;
	
	/**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
	protected $path;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $uploaderid;
	
	 

	/**
	 * @ORM\ManyToMany(targetEntity="Algo\SiteBundle\Entity\User")
	 * 
	 */
    private $usersallowed;
    
    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="documents")
     * @ORM\JoinColumn(name="roleid", referencedColumnName="id")
     */
    protected $role;
    
     
    /**
     * @ORM\Column(type="boolean")
     */
    protected $public;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $uploaded;
    
    /**
     * @Assert\File(maxSize="50000000")
     */
    private $file;
    
    protected  $temp;
    
    public  function __construct()
    {
    $this->usersallowed = new ArrayCollection();
    $this->setUploaded(new \DateTime());
    }
    
    
    public function getTemp()
    {
    	return $this->temp;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
    	$this->file = $file;
    	$this->setFilename($this->getFile()->getClientOriginalName());
    	
    	// check if we have an old image path
    	if (isset($this->path)) {
    		// store the old name to delete after the update
    		$this->temp = $this->path;
    		$this->path = null;
    	} else {
    		$this->path = 'initial';
    	}
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
    	if (null !== $this->getFile()) {
    		$this->setFilename($this->getFile()->getClientOriginalName());
    		// do whatever you want to generate a unique name
    		$filename = sha1(uniqid(mt_rand(), true));
    		$this->path = $filename.'.'.$this->getFile()->guessExtension();
    		//$this->filename=$this->path;
    	}
    	
    }
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
    	if (null === $this->getFile()) {
    		return;
    	}
    	
    	// if there is an error when moving the file, an exception will
    	// be automatically thrown by move(). This will properly prevent
    	// the entity from being persisted to the database on error
    	$this->getFile()->move($this->getUploadRootDir(), $this->path);
    
    	// check if we have an old image
    	if (isset($this->temp)) {
    		// delete the old image
    		unlink($this->getUploadRootDir().'/'.$this->temp);
    		// clear the temp image path
    		$this->temp = null;
    	}
    	$this->file = null;
    }
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
    	if ($file = $this->getAbsolutePath()) {
    		unlink($file);
    	}
    }
    
    public function getUsersallowed()
    {
    	return $this->usersallowed;
    }
    
    
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
    	return $this->file;
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
     * Set filename
     *
     * @param string $filename
     * @return File
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    
        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    

    /**
     * Set descr
     *
     * @param string $descr
     * @return File
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
     * Set path
     *
     * @param string $path
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set uploaderid
     *
     * @param integer $uploaderid
     * @return File
     */
    public function setUploaderid($uploaderid)
    {
        $this->uploaderid = $uploaderid;
    
        return $this;
    }

    /**
     * Get uploaderid
     *
     * @return integer 
     */
    public function getUploaderid()
    {
        return $this->uploaderid;
    }

    /**
     * Set roles
     *
     * @param string $roles
     * @return File
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    
        return $this;
    }

    /**
     * Get roles
     *
     * @return string 
     */
    public function getRoles()
    {
        return $this->roles;
    }

   
    /**
     * Set public
     *
     * @param boolean $public
     * @return File
     */
    public function setPublic($public)
    {
        $this->public = $public;
    
        return $this;
    }

    /**
     * Get public
     *
     * @return boolean 
     */
    public function getPublic()
    {
        return $this->public;
    }
    
    public function getAbsolutePath()
    {
    	return null === $this->path
    	? null
    	: $this->getUploadRootDir().'/'.$this->path;
    }
    
    public function getWebPath()
    {
    	return null === $this->path
    	? null
    	: $this->getUploadDir().'/'.$this->path;
    }
    
    protected function getUploadRootDir()
    {
    	// the absolute directory path where uploaded
    	// documents should be saved
    	return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    
    protected function getUploadDir()
    {
    	// get rid of the __DIR__ so it doesn't screw up
    	// when displaying uploaded doc/image in the view.
    	return 'uploads/documents';
    }
    
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
    	$metadata->addPropertyConstraint('file', new Assert\File(array(
    			'maxSize' => 50000000,
    	)));
    }
    
    

    /**
     * Add usersallowed
     *
     * @param \Algo\SiteBundle\Entity\User $usersallowed
     * @return Document
     */
    public function addUsersallowed(\Algo\SiteBundle\Entity\User $usersallowed)
    {
        $this->usersallowed[] = $usersallowed;
    
        return $this;
    }

    /**
     * Remove usersallowed
     *
     * @param \Algo\SiteBundle\Entity\User $usersallowed
     */
    public function removeUsersallowed(\Algo\SiteBundle\Entity\User $usersallowed)
    {
        $this->usersallowed->removeElement($usersallowed);
    }

    /**
     * Set role
     *
     * @param \Algo\SiteBundle\Entity\Role $role
     * @return Document
     */
    public function setRole(\Algo\SiteBundle\Entity\Role $role = null)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return \Algo\SiteBundle\Entity\Role 
     */
    public function getRole()
    {
        return $this->role;
    }

    

    /**
     * Set shortdescr
     *
     * @param string $shortdescr
     * @return Document
     */
    public function setShortdescr($shortdescr)
    {
        $this->shortdescr = $shortdescr;
    
        return $this;
    }

    /**
     * Get shortdescr
     *
     * @return string 
     */
    public function getShortdescr()
    {
        return $this->shortdescr;
    }

    /**
     * Set uploaded
     *
     * @param \DateTime $uploaded
     * @return Document
     */
    public function setUploaded($uploaded)
    {
        $this->uploaded = $uploaded;
    
        return $this;
    }

    /**
     * Get uploaded
     *
     * @return \DateTime 
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }
    
    public function __toString() {
    	try {
    		return (string) $this->id;
    	} catch (Exception $exception) {
    		return '';
    	}
    }
}