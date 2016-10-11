<?php

namespace EmcVoteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Campaign
 *
 * @ORM\Table(name="Camp")
 * @ORM\Entity
 *
 * @JMS\ExclusionPolicy("NONE")
 */
class Campaign
{
    /**
     * @var integer
     *
     * @ORM\Column(name="CampID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $campaignId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Start", type="datetime", nullable=false)
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Finish", type="datetime", nullable=false)
     */
    private $finish;

    /**
     * @var integer
     *
     * @ORM\Column(name="Ballots", type="integer", nullable=false)
     */
    private $ballots;

    /**
     * @var string
     *
     * @ORM\Column(name="Descr", type="string", length=255, nullable=false)
     */
    private $description;



    /**
     * Get campaignId
     *
     * @return integer 
     */
    public function getCampaignId()
    {
        return $this->campaignId;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     * @return Campaign
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set finish
     *
     * @param \DateTime $finish
     * @return Campaign
     */
    public function setFinish($finish)
    {
        $this->finish = $finish;

        return $this;
    }

    /**
     * Get finish
     *
     * @return \DateTime 
     */
    public function getFinish()
    {
        return $this->finish;
    }

    /**
     * Set ballots
     *
     * @param integer $ballots
     * @return Campaign
     */
    public function setBallots($ballots)
    {
        $this->ballots = $ballots;

        return $this;
    }

    /**
     * Get ballots
     *
     * @return integer 
     */
    public function getBallots()
    {
        return $this->ballots;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Campaign
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
}
