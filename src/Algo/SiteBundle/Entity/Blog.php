<?php

// src/Algo/SiteBundle/Entity/Blog.php

namespace Algo\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog")
 * @ORM\HasLifecycleCallbacks
 */

class Blog
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
	protected $title;

	/**
	 * @ORM\Column(type="string", length=100)
	 */
	protected $author;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $blog;

	/**
	 * @ORM\Column(type="string", length=20, nullable=true)
	 */
	protected $image;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $tags;

	protected $comments;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $created;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $updated;
	
	/**
	 * @ORM\Column(type="text")
	 */
	protected  $brief;
	
	/**
	 * @Assert\File(maxSize="6000000")
	 */
	private $imagefile;
	
	
	
	
	
	
	public function __construct()
	{
		$this->setCreated(new \DateTime());
		$this->setUpdated(new \DateTime());
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
     * Set title
     *
     * @param string $title
     * @return Blog
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Blog
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set blog
     *
     * @param string $blog
     * @return Blog
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;
    
        return $this;
    }

    /**
     * Get blog
     *
     * @return string 
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Blog
     */
    public function setImage($image)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * Sets image file.
     *
     * @param UploadedFile $imagefile
     */
    
    public function setImagefile(UploadedFile $imagefile = null)
    {
    	$this->imagefile = $imagefile;
    }
    
    /**
     * Get image file.
     *
     * @return UploadedFile
     */
    public function getImagefile()
    {
    	return $this->imagefile;
    }

    /**
     * Set tags
     *
     * @param string $tags
     * @return Blog
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    
        return $this;
    }

    /**
     * Get tags
     *
     * @return string 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Blog
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Blog
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
    	$this->setUpdated(new \DateTime());
    }
    
    public function setBrief($brief)
    {
    	$this->brief=$brief;
    }
    
    public function  getBrief()
    {
    	return $this->brief;
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
    	return 'images';
    }
    
    
    public function upload()
    {
    	// the file property can be empty if the field is not required
    	
    	if (null === $this->getImagefile()) {
    		
    		return;
    }
    
    
    
    	// use the original file name here but you should
    	// sanitize it at least to avoid any security issues
    
    	// move takes the target directory and then the
    	// target filename to move to
    	
    	
    	$this->getImagefile()->move(
    			$this->getUploadRootDir(),
    			$this->getImagefile()->getClientOriginalName()
    	);
    
    	// set the image property to the filename where you've saved the file
    	$this->image = $this->getImagefile()->getClientOriginalName();
    
    	// clean up the file property as you won't need it anymore
    	$this->imagefile = null;
    }
    
    
    public function createBrief()
    {
    	$summarytext=$this->getBlog();
    	$endchar=strpos($summarytext,'.',100);
    	if(!$endchar)
    	{
    		$this->setBrief($summarytext);
    		return ;
    	}
    	$brief=substr($summarytext,0,$endchar+1);
    	$this->setBrief($brief); 
    }
    
    
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
    	$metadata->addPropertyConstraint('title', new NotBlank());
    
    	$metadata->addPropertyConstraint('author', new NotBlank());
    	
    	$metadata->addPropertyConstraint('imagefile', new Assert\File(array(
    			'maxSize' => 6000000,)));
    
    	// $metadata->addPropertyConstraint('image', new NotBlank());
    	$metadata->addPropertyConstraint('blog', new NotBlank());
    
    	$metadata->addPropertyConstraint('tags', new NotBlank());
    }
}