<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Posting
 *
 * @ORM\Table(name="posting")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostingRepository")
 * @ORM\HasLifecycleCallbacks 
 */
class Posting
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var File
     *
     * @ORM\Column(name="image", type="string", length=255)
     * @Assert\File(mimeTypes={ "image/jpg" }) 
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="seller_id", type="integer")
     */
    private $sellerId;
    
    /**
     * @var int
     *
     * @ORM\Column(name="points", type="integer")
     */
    private $points;    
    
    /**
     * @var float
     *
     * @ORM\Column(name="co2_saved", type="float")
     */
    private $co2_saved;    

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="string", length=255)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, options={ "default": "open" })
     */
    private $status;
    
    /* 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Request", mappedBy="posting_id")
     */
    private $requests;
    
    private $seller;
    public function getSeller() {
    	return $this->seller;
    }
    public function setSeller($seller) {
    	$this->seller = $seller;
    	return $this;
    }    
    
    public function __construct() {
    	$this->requests = new ArrayCollection();
    	$this->price = "";
    	$this->status = "open";
    }
    /**
     *  @ORM\PrePersist
     */
    public function doStuffOnPrePersist()
    {
    	$this->createdAt = new \DateTime();
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Posting
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
     * Set title
     *
     * @param string $title
     *
     * @return Posting
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
     * Set description
     *
     * @param string $description
     *
     * @return Posting
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Posting
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Posting
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set sellerId
     *
     * @param integer $sellerId
     *
     * @return Posting
     */
    public function setSellerId($sellerId)
    {
        $this->sellerId = $sellerId;

        return $this;
    }

    /**
     * Get sellerId
     *
     * @return int
     */
    public function getSellerId()
    {
        return $this->sellerId;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Posting
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Posting
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return Posting
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set co2Saved
     *
     * @param float $co2Saved
     *
     * @return Posting
     */
    public function setCo2Saved($co2Saved)
    {
        $this->co2_saved = $co2Saved;

        return $this;
    }

    /**
     * Get co2Saved
     *
     * @return float
     */
    public function getCo2Saved()
    {
        return $this->co2_saved;
    }
    
    /**
     *
     * @return ArrayCollection
     */
    public function getRequests()
    {
    	return $this->requests;
    }
    
    /**
     *
     * @return Posting
     */
    public function setRequests($requests)
    {
    	$this->requests = $requests;
    }    
}
