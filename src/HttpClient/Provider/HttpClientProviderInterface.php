<?php

namespace Sainsburys\TechTest\HttpClient\Provider;

use Sainsburys\TechTest\HttpClient\HttpClientInterface;

/**
 * Interface HttpClientProviderInterface
 * @package Sainsburys\TechTest\HttpClient\Provider
 */
interface HttpClientProviderInterface
{
    /**
     * return a new class that implements CurlHttpClientInterface
     *
     * @param string $url
     * @return HttpClientInterface
     */
    public function provideClient($url = null);
}
