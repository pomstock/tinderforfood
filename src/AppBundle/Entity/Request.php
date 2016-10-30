<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Request
 *
 * @ORM\Table(name="request")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RequestRepository")
 * @ORM\HasLifecycleCallbacks  
 */
class Request
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
     * @var int
     *
     * @ORM\Column(name="buyer_id", type="integer")
     */
    private $buyerId;
    
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
     * @ORM\Column(name="posting_id", type="integer")
	 * @ORM\ManyToOne(targetEntity="Posting", inversedBy="requests")
     */
    private $postingId;

    /**
     * @var string
     * 
     *
     * @ORM\Column(name="status", type="string", length=255, options={ "default": "pending" })
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="buyer_confirmed_at", type="datetime", nullable=true)
     */
    private $buyerConfirmedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="seller_confirmed_at", type="datetime", nullable=true)
     */
    private $sellerConfirmedAt;
    
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
     * @ORM\Column(name="text", type="text")
     */
    private $text;
    
    
    private $buyer;
    public function getBuyer() {
    	return $this->buyer;
    }
    public function setBuyer($buyer) {
    	$this->buyer = $buyer;
    	return $this;
    }
    
    private $posting;
    public function getPosting() {
    	return $this->posting;
    }
    public function setPosting($posting) {
    	$this->posting = $posting;
    	return $this;
    }
    
    /**
     *  @ORM\PrePersist
     */
    public function doStuffOnPrePersist()
    {
    	$this->createdAt = new \DateTime();
    	$this->status = 'pending';
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
     * Set buyerId
     *
     * @param integer $buyerId
     *
     * @return Request
     */
    public function setBuyerId($buyerId)
    {
        $this->buyerId = $buyerId;

        return $this;
    }

    /**
     * Get buyerId
     *
     * @return int
     */
    public function getBuyerId()
    {
        return $this->buyerId;
    }

    /**
     * Set postingId
     *
     * @param integer $postingId
     *
     * @return Request
     */
    public function setPostingId($postingId)
    {
        $this->postingId = $postingId;

        return $this;
    }

    /**
     * Get postingId
     *
     * @return int
     */
    public function getPostingId()
    {
        return $this->postingId;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Request
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
     * Set buyerConfirmedAt
     *
     * @param \DateTime $buyerConfirmedAt
     *
     * @return Request
     */
    public function setBuyerConfirmedAt($buyerConfirmedAt)
    {
        $this->buyerConfirmedAt = $buyerConfirmedAt;

        return $this;
    }

    /**
     * Get buyerConfirmedAt
     *
     * @return \DateTime
     */
    public function getBuyerConfirmedAt()
    {
        return $this->buyerConfirmedAt;
    }

    /**
     * Set sellerConfirmedAt
     *
     * @param \DateTime $sellerConfirmedAt
     *
     * @return Request
     */
    public function setSellerConfirmedAt($sellerConfirmedAt)
    {
        $this->sellerConfirmedAt = $sellerConfirmedAt;

        return $this;
    }

    /**
     * Get sellerConfirmedAt
     *
     * @return \DateTime
     */
    public function getSellerConfirmedAt()
    {
        return $this->sellerConfirmedAt;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Request
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Request
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
     * @return Request
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
     * Set points
     *
     * @param integer $points
     *
     * @return Request
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
     * @return Request
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
}
