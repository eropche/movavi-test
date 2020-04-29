<?php

namespace Tests\Provider;

use PHPUnit\Framework\TestCase;
use MovaviRate\Entity\Currency;
use MovaviRate\Provider\CBRProvider;

class CBRProviderTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\MovaviRate\Client\ClientInterface
     */
    private $client;

    /**
     * @var CBRProvider
     */
    private $service;

    public function setUp(): void
    {
        $this->client  = $this->createMock(\MovaviRate\Client\ClientInterface::class);
        $this->service = new CBRProvider($this->client);
    }

    public function testGetRateException()
    {
        $this->expectExceptionMessage('Fail for cbr.ru');
        $this->service->getRate(Currency::USD, new \DateTimeImmutable());
    }

    public function testGetRateException2()
    {
        $this->client->method('get')->willReturn('fail xml');
        $this->expectExceptionMessage("simplexml_load_string(): Entity: line 1: parser error : Start tag expected, '<' not found");
        $this->service->getRate(Currency::USD, new \DateTimeImmutable());
    }

    public function testGetRateException3()
    {
        $this->client->method('get')->willReturn('<ValCurs></ValCurs>');
        $this->expectExceptionMessage(sprintf('Currency not found: %s', Currency::USD));
        $this->service->getRate(Currency::USD, new \DateTimeImmutable());
    }

    public function testGetRateException4()
    {
        $xml = '<ValCurs Date="30.04.2020" name="Foreign Currency Market"><Valute ID="R03023">
                <NumCode>474</NumCode>
                <CharCode>EUR</CharCode>
                <Nominal>1</Nominal>
                <Name>Евро</Name>
                <Value>82,45</Value>
                </Valute></ValCurs>'
        ;
        $this->client->method('get')->willReturn($xml);
        $this->expectExceptionMessage(sprintf('Currency not found: %s', Currency::USD));
        $this->service->getRate(Currency::USD, new \DateTimeImmutable());
    }

    public function testGetRate()
    {
        $xml = '<ValCurs Date="30.04.2020" name="Foreign Currency Market"><Valute ID="R03023">
                <NumCode>510</NumCode>
                <CharCode>USD</CharCode>
                <Nominal>1</Nominal>
                <Name>Доллар США</Name>
                <Value>75,41</Value>
                </Valute></ValCurs>'
        ;
        $date = new \DateTimeImmutable('30.04.2020');
        $this->client->method('get')->willReturn($xml);
        $rate = $this->service->getRate(Currency::USD, $date);
        $this->assertEquals($rate->getRateFloat(), 75.41);
        $this->assertEquals($rate->getDate(), $date);
    }
}
