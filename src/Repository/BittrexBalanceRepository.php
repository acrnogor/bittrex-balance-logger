<?php

namespace App\Repository;

use App\Entity\BittrexBalance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BittrexBalanceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BittrexBalance::class);
    }

    /**
     * @param BittrexBalance $bittrexBalance
     */
    public function save(BittrexBalance $bittrexBalance)
    {
        $this->_em->persist($bittrexBalance);
        $this->_em->flush();
    }
}
