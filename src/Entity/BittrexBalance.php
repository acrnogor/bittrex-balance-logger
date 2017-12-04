<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BittrexBalanceRepository")
 */
class BittrexBalance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", nullable=false, type="datetime")
     */
    private $createdAt;

    /**
     * @var float
     * @ORM\Column(name="balance", nullable=false, type="float")
     */
    private $balance;

    /**
     * @var int
     * @ORM\Column(name="btc_usd", nullable=false, type="integer")
     */
    private $btcUsd;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return BittrexBalance
     */
    public function setCreatedAt(\DateTime $createdAt): BittrexBalance
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     * @return BittrexBalance
     */
    public function setBalance(float $balance): BittrexBalance
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @return float
     */
    public function getBtcUsd(): float
    {
        return $this->btcUsd;
    }

    /**
     * @param int $btcUsd
     * @return BittrexBalance
     */
    public function setBtcUsd(int $btcUsd): BittrexBalance
    {
        $this->btcUsd = $btcUsd;
        return $this;
    }
}
