<?php

namespace App\Command;

use App\Entity\BittrexBalance;
use App\Repository\BittrexBalanceRepository;
use App\Service\BittrexService;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BittrexFetchCommand extends Command
{
    /**
     * @var Client
     */
    private $bittrex;

    /**
     * @var BittrexBalanceRepository
     */
    private $bittrexBalanceRepository;

    /**
     * BittrexFetchCommand constructor.
     * @param BittrexService $bittrexService
     * @param BittrexBalanceRepository $bbRepository
     */
    public function __construct(BittrexService $bittrexService, BittrexBalanceRepository $bbRepository)
    {
        parent::__construct();
        $this->bittrex = $bittrexService;
        $this->bittrexBalanceRepository = $bbRepository;
    }

    protected function configure()
    {
        $this
            ->setName('bittrex:fetch')
            ->setDescription('Fetch data from Bittrex exchange.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $balance = $this->bittrex->getBalance();
        $totalBtcBalance = $this->calculateTotalBtcBalance($balance);

        // how much is btc in usd
        $btcResponse = $this->bittrex->getMarketSummary('usdt-btc');
        $currentBtcValueInUsd = $btcResponse['result'][0]['Last'];

        // store this
        $balanceEntity = new BittrexBalance();
        $balanceEntity
            ->setCreatedAt(new \DateTime())
            ->setBalance($totalBtcBalance)
            ->setBtcUsd($currentBtcValueInUsd);

        $this->bittrexBalanceRepository->save($balanceEntity);

        // show nice output
        $output->writeln(' <info> Bittrex Sniffer v0.1</info> ');
        $output->writeln(sprintf(' <comment>Total BTC    : </comment>%s', $totalBtcBalance));
        $output->writeln(sprintf(' <comment>1 BTC = USD  : </comment>$%s', (int) $currentBtcValueInUsd));
        $output->writeln(sprintf(' <comment>Value in USD : </comment>$%s', (int) ($totalBtcBalance * $currentBtcValueInUsd)));

    }

    /**
     * @param $balance
     * @return float
     */
    protected function calculateTotalBtcBalance(array $balance): float
    {
        $totalBtcBalance = 0;

        foreach ($balance['result'] as $myCurrencyKey => $myCurrency) {
            if ($myCurrencyKey === 'BTC') {
                $totalBtcBalance += $myCurrency['Balance'];
            } else {

                $response = $this->bittrex->getMarketSummary('BTC-'. $myCurrencyKey);
                $currencySummary = $response['result'][0];
                $currencyInBtc = round($currencySummary['Last'] * $myCurrency['Balance'], 8);
                $totalBtcBalance += $currencyInBtc;
            }
        }

        return $totalBtcBalance;
    }
}