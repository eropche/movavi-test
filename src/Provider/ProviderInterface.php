<?php

namespace MovaviRate\Provider;

use MovaviRate\Entity\Rate;

interface ProviderInterface
{
    public function getRate(string $exchangeFrom, \DateTimeImmutable $date): Rate;
}
