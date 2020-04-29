<?php

namespace MovaviRate\Entity;

use DateTimeImmutable;

class Rate
{
    /**
     * @var string
     */
    private $exchangeFrom;

    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * @var float
     */
    private $rateFloat;

    /**
     * @var string
     */
    private $rateString;

    /**
     * Rate constructor.
     * @param string $exchangeFrom
     * @param DateTimeImmutable $date
     * @param float|string $rate
     */
    public function __construct(string $exchangeFrom, DateTimeImmutable $date, $rate)
    {
        $this->exchangeFrom = $exchangeFrom;
        $this->date         = $date;
        $this->rateFloat    = (float) $rate;
        $this->rateString   = (string) $rate;
    }

    /**
     * @return string
     */
    public function getExchangeFrom(): string
    {
        return $this->exchangeFrom;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return float
     */
    public function getRateFloat(): float
    {
        return (float) $this->rateFloat;
    }
}
