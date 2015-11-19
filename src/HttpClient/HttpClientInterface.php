<?php

namespace Sainsburys\TechTest\HttpClient;


/**
 * Interface HttpClientInterface
 * @package Sainsburys\TechTest\RequestMaker
 */
interface HttpClientInterface
{
    /**
     * send the request
     */
    public function sendRequest();

    /**
     * get content
     *
     * @return string
     */
    public function getContent();

    /**
     * get status code
     *
     * @return int
     */
    public function getStatusCode();
}
