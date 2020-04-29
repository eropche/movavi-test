<?php

namespace Tests\Provider;

use PHPUnit\Framework\TestCase;
use MovaviRate\Entity\Currency;;
use MovaviRate\Provider\RBCProvider;

class RBCProviderTest extends TestCase
{
    /**
     * @var RBCProvider
     */
    private $service;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\MovaviRate\Client\ClientInterface
     */
    private $client;

    public function setUp(): void
    {
        $this->client  = $this->createMock(\MovaviRate\Client\ClientInterface::class);
        $this->service = new RBCProvider($this->client);
    }

    public function testGetRateException()
    {
        $this->expectExceptionMessage('Fail for rbc.ru');
        $this->service->getRate(Currency::USD, new \DateTimeImmutable());
    }

    public function testGetRateException2()
    {
        $this->client->method('get')->willReturn('fail json');
        $this->expectExceptionMessage('Syntax error');
        $this->service->getRate(Currency::USD, new \DateTimeImmutable());
    }

    public function testGetRateException3()
    {
        $this->expectExceptionMessage('Service status: 0');
        $this->client->method('get')->willReturn('{}');
        $this->service->getRate(Currency::USD, new \DateTimeImmutable());
    }

    public function testGetRateException4()
    {
        $this->expectExceptionMessage('Service status: 500');
        $this->client->method('get')->willReturn('{"status": 500}');
        $this->service->getRate(Currency::USD, new \DateTimeImmutable());
    }

    public function testGetRateException5()
    {
        $this->expectExceptionMessage('Fail rate data');
        $this->client->method('get')->willReturn('{"status": 200, "data": {}}');
        $this->service->getRate(Currency::USD, new \DateTimeImmutable());
    }

    public function testGetRate()
    {
        $json = '{"status": 200, "meta": {"sum_deal": 1.0, "source": "cbrf", "currency_from": "USD", "date": null, "currency_to": "RUR"}, "data": {"date": "2020-04-30 18:10:11", "sum_result": 73.2141, "rate1": 73.2141, "rate2": 0.05}}';
        $this->client->method('get')->willReturn($json);
        $date = new \DateTimeImmutable('2020-04-30');
        $rate = $this->service->getRate(Currency::USD, $date);
        $this->assertEquals($rate->getRateFloat(), 73.2141);
        $this->assertEquals($rate->getDate(), $date);
    }
}
