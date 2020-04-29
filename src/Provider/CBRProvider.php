<?php

namespace MovaviRate\Provider;

use DateTimeImmutable;
use MovaviRate\Entity\Rate;
use MovaviRate\Client\ClientInterface;

class CBRProvider implements ProviderInterface
{
    private const DATE_FORMAT = 'd/m/Y';

    private $url = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=%s';

    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $exchangeFrom
     * @param DateTimeImmutable $date
     * @return Rate
     */
    public function getRate(string $exchangeFrom, DateTimeImmutable $date): Rate
    {
        $exchangeFrom = mb_strtoupper($exchangeFrom);
        $url          = sprintf($this->url, $date->format(self::DATE_FORMAT));
        $content      = $this->client->get($url);

        if (!$content) {
            throw new \Exception('Fail for cbr.ru');
        }

        try {
            $object = simplexml_load_string($content);
        } catch (\Throwable $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode(), $exception);
        }

        if (libxml_get_errors()) {
            throw new \Exception(implode(PHP_EOL, libxml_get_errors()));
        }

        $rate = 0;
        $isFound = false;
        foreach ($object->Valute as $item) {
            if ((string) $item->CharCode === $exchangeFrom) {
                $rate = (float) str_replace(',', '.', (string) $item->Value);
                $isFound = true;
                break;
            }
        }

        if (!$isFound) {
            throw new \Exception(sprintf('Currency not found: %s', $exchangeFrom));
        }

        return new Rate($exchangeFrom, $date, $rate);
    }
}