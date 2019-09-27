<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

/**
 * Description of RateService
 *
 * @author pareshpate
 */
class RateService {
    
    /** @var string $exchange_api_url */
    private $exchange_api_url;
    
    /** @var string $exchange_api_url */
    private $exchange_base_currency;
    
    public function __construct($config){
        $this->exchange_api_url = $config['exchange_api_url'];
        $this->exchange_base_currency = $config['exchange_base_currency'];
    }
    
    public function refreshRate($currencyId)
    {
        $exchangeApiUrl = $this->exchange_api_url;
        $exchangeBaseCurrency = $this->exchange_base_currency;
        
        // fetch the exchange rates
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', $exchangeApiUrl . 'latest?base=' . $exchangeBaseCurrency);
        $content = $response->getContent();
        $content = $response->toArray();
        $rates = $content['rates'];
        $convertedRate = $rates[trim($currencyId)];
        return $convertedRate;
    }
}
