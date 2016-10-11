<?php

namespace EmcVoteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Candidate
 *
 * @ORM\Table(name="CampTo")
 * @ORM\Entity
 *
 * @JMS\ExclusionPolicy("NONE")
 */
class Candidate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="CampID", type="integer", nullable=false)
     *
     * @JMS\Exclude()
     */
    private $campaignId;

    /**
     * @var integer
     *
     * @ORM\Column(name="ToID", type="integer", nullable=false)
     */
    private $toId;

    /**
     * @var string
     *
     * @ORM\Column(name="ToAddr", type="string", length=35, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $addr;

    /**
     * @var string
     *
     * @ORM\Column(name="ToName", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="ToDesc", type="string", length=255, nullable=false)
     */
    private $description;

    private $percents;

    /**
     * Set candidateId
     *
     * @param integer $campaignId
     * @return Candidate
     */
    public function setCampaignId($campaignId)
    {
        $this->campaignId = $campaignId;

        return $this;
    }

    /**
     * Get candidateId
     *
     * @return integer 
     */
    public function getCampaignId()
    {
        return $this->campaignId;
    }

    /**
     * Set toId
     *
     * @param integer $toId
     * @return Candidate
     */
    public function setToId($toId)
    {
        $this->toId = $toId;

        return $this;
    }

    /**
     * Get toId
     *
     * @return integer 
     */
    public function getToId()
    {
        return $this->toId;
    }

    /**
     * Get addr
     *
     * @return string 
     */
    public function getAddr()
    {
        return $this->addr;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Candidate
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

    /**
     * Set description
     *
     * @param string $description
     * @return Candidate
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
     * @return mixed
     */
    public function getPercents()
    {
        return $this->percents;
    }

    /**
     * @param mixed $percents
     */
    public function setPercents($percents)
    {
        $this->percents = $percents;
    }
}
