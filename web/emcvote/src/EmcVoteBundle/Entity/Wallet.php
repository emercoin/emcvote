<?php

namespace EmcVoteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wallet
 *
 * @ORM\Table(name="Wallet")
 * @ORM\Entity
 */
class Wallet
{
    /**
     * @var string
     *
     * @ORM\Column(name="StartB", type="string", length=64, nullable=false)
     */
    private $startb;

    /**
     * @var string
     *
     * @ORM\Column(name="LastB", type="string", length=64, nullable=false)
     */
    private $lastb;

    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set startb
     *
     * @param string $startb
     * @return Wallet
     */
    public function setStartb($startb)
    {
        $this->startb = $startb;

        return $this;
    }

    /**
     * Get startb
     *
     * @return string 
     */
    public function getStartb()
    {
        return $this->startb;
    }

    /**
     * Set lastb
     *
     * @param string $lastb
     * @return Wallet
     */
    public function setLastb($lastb)
    {
        $this->lastb = $lastb;

        return $this;
    }

    /**
     * Get lastb
     *
     * @return string 
     */
    public function getLastb()
    {
        return $this->lastb;
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
