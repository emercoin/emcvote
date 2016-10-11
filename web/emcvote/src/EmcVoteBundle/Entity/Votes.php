<?php

namespace EmcVoteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Votes
 *
 * @ORM\Table(name="Votes", indexes={@ORM\Index(name="WhoAddr", columns={"WhoAddr", "CampID"})})
 * @ORM\Entity
 *
 * @JMS\ExclusionPolicy("NONE")
 */
class Votes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="CampID", type="integer", nullable=false)
     * @JMS\Exclude()
     */
    private $campaignId;

    /**
     * @var string
     *
     * @ORM\Column(name="TXID", type="string", length=64, nullable=true)
     */
    private $txid;

    /**
     * @var string
     *
     * @ORM\Column(name="WhoAddr", type="string", length=35, nullable=false)
     */
    private $whoaddr;

    /**
     * @var integer
     *
     * @ORM\Column(name="ToID", type="integer", nullable=false)
     */
    private $toid;

    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMS\Exclude()
     */
    private $id;

    /**
     * Set campaignId
     *
     * @param integer $campaignId
     * @return Votes
     */
    public function setCampaignId($campaignId)
    {
        $this->campaignId = $campaignId;

        return $this;
    }

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
     * Set txid
     *
     * @param string $txid
     * @return Votes
     */
    public function setTxid($txid)
    {
        $this->txid = $txid;

        return $this;
    }

    /**
     * Get txid
     *
     * @return string 
     */
    public function getTxid()
    {
        return $this->txid;
    }

    /**
     * Set whoaddr
     *
     * @param string $whoaddr
     * @return Votes
     */
    public function setWhoaddr($whoaddr)
    {
        $this->whoaddr = $whoaddr;

        return $this;
    }

    /**
     * Get whoaddr
     *
     * @return string 
     */
    public function getWhoaddr()
    {
        return $this->whoaddr;
    }

    /**
     * Set toid
     *
     * @param integer $toid
     * @return Votes
     */
    public function setToid($toid)
    {
        $this->toid = $toid;

        return $this;
    }

    /**
     * Get toid
     *
     * @return integer 
     */
    public function getToid()
    {
        return $this->toid;
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
}
