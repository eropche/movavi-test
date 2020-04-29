<?php

namespace Tests\Provider;

use MovaviRate\Provider\ProviderFactory;
use PHPUnit\Framework\TestCase;

class ProviderFactoryTest extends TestCase
{
    public function testGetProviders()
    {
        $client  = $this->createMock(\MovaviRate\Client\ClientInterface::class);
        $factory = new ProviderFactory($client);
        $this->assertNotEmpty($factory);
        $this->assertIsObject($factory);
        $providers = $factory->getProviders();
        $this->assertNotEmpty($providers);
        $this->assertIsArray($providers);
        $this->assertGreaterThanOrEqual(2, count($providers));
    }
}
