<?php

namespace EmcVoteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ballot
 *
 * @ORM\Table(name="Ballot")
 * @ORM\Entity
 */
class Ballot
{
    /**
     * @var integer
     *
     * @ORM\Column(name="CampID", type="integer", nullable=false)
     */
    private $campid;

    /**
     * @var string
     *
     * @ORM\Column(name="TXID", type="string", length=64, nullable=false)
     */
    private $txid;

    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set campid
     *
     * @param integer $campid
     * @return Ballot
     */
    public function setCampid($campid)
    {
        $this->campid = $campid;

        return $this;
    }

    /**
     * Get campid
     *
     * @return integer 
     */
    public function getCampid()
    {
        return $this->campid;
    }

    /**
     * Set txid
     *
     * @param string $txid
     * @return Ballot
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
