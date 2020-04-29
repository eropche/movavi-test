<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use MovaviRate\RateService;

class RateServiceTest extends TestCase
{
    public function testGetRate()
    {
        $firstRate = '76.05';
        $secondRate = '71.04';
        $date = new \DateTimeImmutable();
        $rate1 = new \MovaviRate\Entity\Rate(\MovaviRate\Entity\Currency::USD, $date, $firstRate);
        $rate2 = new \MovaviRate\Entity\Rate(\MovaviRate\Entity\Currency::USD, $date, $secondRate);
        $provider1 = $this->createMock(\MovaviRate\Provider\ProviderInterface::class);
        $provider1->method('getRate')->willReturn($rate1);

        $provider2 = $this->createMock(\MovaviRate\Provider\ProviderInterface::class);
        $provider2->method('getRate')->willReturn($rate2);

        $service = new RateService($provider1, $provider2);
        $value = $service->getRate(\MovaviRate\Entity\Currency::USD, $date);
        $this->assertEquals($value, '73.545');
    }

    public function testGetRateException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Providers not found');
        $service = new RateService();
        $service->getRate(\MovaviRate\Entity\Currency::USD, new \DateTimeImmutable());
    }
}
