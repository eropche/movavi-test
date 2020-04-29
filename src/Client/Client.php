<?php

namespace MovaviRate\Client;

/**
 * Class Client
 * @package MovaviRate\Provider
 */
class Client implements ClientInterface
{
    /**
     * @param $url
     * @return false|string
     * @throws \Exception
     */
    public function get(string $url)
    {
        if (ini_get('allow_url_fopen') === false) {
            throw new \Exception('Need allow_url_fopen');
        }

        return file_get_contents($url);
    }
}