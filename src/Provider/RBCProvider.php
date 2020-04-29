<?php

namespace MovaviRate\Provider;

use DateTimeImmutable;
use MovaviRate\Entity\Rate;
use MovaviRate\Client\ClientInterface;

class RBCProvider implements ProviderInterface
{
    private const DATE_FORMAT = 'Y-m-d';

    private $url = 'https://cash.rbc.ru/cash/json/converter_currency_rate/?currency_from=%s&currency_to=RUR&source=cbrf&sum=1&date=%s';

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
        $url          = sprintf($this->url, $exchangeFrom, $date->format(self::DATE_FORMAT));
        $content      = $this->client->get($url);

        if (!$content) {
            throw new \Exception('Fail for rbc.ru');
        }

        $data = json_decode($content, true);
        if (json_last_error() > 0) {
            throw new \Exception(json_last_error_msg());
        }

        if (!isset($data['status']) || $data['status'] !== 200) {
            throw new \Exception(sprintf('Service status: %d',
                $data['status'] ?? 'fail'));
        }

        if (!isset($data['data']['rate1']) || empty($data['data']['rate1'])) {
            throw new \Exception('Fail rate data');
        }

        return new Rate($exchangeFrom, $date, $data['data']['rate1']);
    }
}
