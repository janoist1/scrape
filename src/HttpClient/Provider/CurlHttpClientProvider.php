<?php

namespace Sainsburys\TechTest\HttpClient\Provider;
use Sainsburys\TechTest\HttpClient\CurlHttpClient;


/**
 * Class CurlHttpClientProvider
 * @package Sainsburys\TechTest\HttpClient\Provider
 */
class CurlHttpClientProvider implements HttpClientProviderInterface
{
    /**
     * return a new CurlHttpClient
     *
     * @param null $url
     * @return CurlHttpClient
     */
    public function provideClient($url = null)
    {
        return new CurlHttpClient($url);
    }
}
