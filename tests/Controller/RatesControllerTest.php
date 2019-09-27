<?php

namespace App\Tests\Controller;

use App\Entity\Rates;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RatesControllerTest extends WebTestCase
{
    public function testRatesNewRate()
    {
        $rate = rand(1,100);
        $currency = rand(1,2);

        $client = static::createClient();
        $crawler = $client->request('GET', '/rates/new');
        $form = $crawler->selectButton('Save')->form([
            'rates[rate]' => $rate,
            'rates[currency]' => $currency,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }
    
    public function testRatesRefreshRate()
    {
        $currency = rand(1,2);
        
        $client = static::createClient();
        $crawler = $client->request('GET', '/rates/form');
        $form = $crawler->selectButton('Save')->form([
            'rates[currency]' => $currency,
        ]);
        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }
    
    public function testRatesEditRate()
    {
        $currency = rand(1,2);
        $rate = rand(1,100);
        $client = static::createClient();
        $crawler = $client->request('GET', '/rates/1/edit');
        $form = $crawler->selectButton('Update')->form([
            'rates[rate]' => $rate,
            'rates[currency]' => $currency,
        ]);
        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }
    
    public function testRatesDeleteRate()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/rates/1/edit');
        $client->submit($crawler->filter('#delete-form')->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }
}
