<?php

namespace MovaviRate\Client;

interface ClientInterface
{
    /**
     * @param string $url
     * @return mixed
     * @throws \Exception
     */
    public function get(string $url);
}
