<?php

namespace MovaviRate\Provider;

use MovaviRate\Client\ClientInterface;

class ProviderFactory
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return ProviderInterface[]
     */
    public function getProviders()
    {
        return [
            new CBRProvider($this->client),
            new RBCProvider($this->client),
        ];

    }
}