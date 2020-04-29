<?php

namespace MovaviRate;

use DateTimeImmutable;
use MovaviRate\Provider\ProviderInterface;

class RateService
{
    /**
     * @var ProviderInterface[]
     */
    private $providers;

    public function __construct(ProviderInterface ...$providers)
    {
        $this->providers = $providers;
    }

    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * @param string $exchangeFrom
     * @param DateTimeImmutable $date
     * @throws \Exception
     */
    public function getRate(string $exchangeFrom, DateTimeImmutable $date)
    {
        if (count($this->providers) == 0) {
            throw new \Exception('Providers not found');
        }

        $sumRate = '0';
        foreach ($this->providers as $provider) {
            $rate     = $provider->getRate($exchangeFrom, $date);
            $sumRate += $rate->getRateFloat();
        }

        return $sumRate / count($this->providers);
    }
}
