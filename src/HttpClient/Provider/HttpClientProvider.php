<?php

namespace Sainsburys\TechTest\HttpClient\Provider;
use Sainsburys\TechTest\HttpClient\CurlHttpClient;


/**
 * Class HttpClientProvider
 * @package Sainsburys\TechTest\HttpClient\Provider
 */
class HttpClientProvider implements HttpClientProviderInterface
{
    /**
     * return a new CurlHttpClient
     *
     * @param null $url
     * @return CurlHttpClient
     */
    public function provideCurlClient($url = null)
    {
        return new CurlHttpClient($url);
    }
}
