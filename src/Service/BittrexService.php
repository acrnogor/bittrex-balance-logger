<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class BittrexService 
{
    private const BASE_URL  = 'https://bittrex.com';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiSecret;

    /**
     * BittrexService constructor.
     * @param string $apiKey
     * @param string $apiSecret
     * @param Client $guzzleHttpClient
     */
    public function __construct(string $apiKey, string $apiSecret, Client $guzzleHttpClient)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        
        $this->client = $guzzleHttpClient;
    }

    /**
     * @return array
     */
    public function getBalance(): array
    {
        $noonce = time();
        $apiPath = sprintf('/api/v1.1/account/getbalances?apikey=%s&nonce=%s', $this->apiKey, $noonce);
        $apiUri = self::BASE_URL . $apiPath;

        $apiSignature = hash_hmac('sha512', $apiUri, $this->apiSecret);

        /** @var Response $response */
        $response = $this->client->get($apiPath, [
            'headers' => [
                'apisign' => $apiSignature
            ]
        ]);

        $responseArray = json_decode($response->getBody()->getContents(), true);
        $responseArray['result'] = $this->removeZeroBalances($responseArray['result']);

        return $responseArray;
    }

    /**
     * @param string $market
     * @return array
     */
    public function getMarketSummary(string $market): array
    {
        $apiPath = sprintf('/api/v1.1/public/getmarketsummary?market=%s', $market);

        /** @var Response $response */
        $response = $this->client->get($apiPath);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param array $balances
     * @return array
     */
    private function removeZeroBalances(array $balances)
    {
        foreach ($balances as $key => $currency) {
            if ((float)$currency['Balance'] === 0.0) {
                unset($balances[$key]);
            } else {
                $balances[$currency['Currency']] = $currency;
                unset($balances[$key]);
            }
        }

        return $balances;
    }
}