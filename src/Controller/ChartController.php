<?php

namespace App\Controller;

use App\Entity\BittrexBalance;
use App\Repository\BittrexBalanceRepository;
use App\Service\BittrexService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChartController extends AbstractController
{
    /**
     * @Route("/", name="default")
     *
     * @param BittrexBalanceRepository $bittrexBalanceRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(BittrexBalanceRepository $bittrexBalanceRepository)
    {
        $balances = $bittrexBalanceRepository->findAll();

        /** @var BittrexBalance $balance */
        foreach ($balances as $balance) {
            $dataSet[] = [
                'balance' => $balance->getBalance(),
//                'btc_value' => $balance->getBtcUsd(),
                'created' => [
                    'Y' => $balance->getCreatedAt()->format('Y'),
                    'm' => $balance->getCreatedAt()->format('m'),
                    'd' => $balance->getCreatedAt()->format('d'),
                    'H' => $balance->getCreatedAt()->format('H'),
                    'i' => $balance->getCreatedAt()->format('i'),
                    's' => $balance->getCreatedAt()->format('s'),
                ]
            ];
        }

        return $this->render('Chart\chart.html.twig', [
            'charts' => $dataSet
        ]);
    }
}
