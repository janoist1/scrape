<?php

namespace Sainsburys\TechTest\HttpClient;

use Sainsburys\TechTest\Exception\HttpClientException;

/**
 * Class CurlHttpClient - a very basic http client using curl
 *
 * @package Sainsburys\TechTest\RequestMaker
 */
class CurlHttpClient implements HttpClientInterface
{
    const ERROR_CURL = 'cURL error - %d %s';
    const ERROR_NO_REQUEST_SENT = 'No request has been sent yet';

    // we need to send valid cookies in order the have access to the LIVE site !
    const COOKIE_FILE = '/../../tmp/cookie.txt';

    /** @var string */
    private $url;

    /** @var string */
    private $content;

    /** @var int */
    private $statusCode;

    /** @var int */
    private $requestSize;

    /**
     * @param string $url - this client depends on a url
     */
    function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * send the request
     *
     * @return string
     * @throws HttpClientException
     */
    public function sendRequest()
    {
        $ch = $this->initCurl();
        $content = curl_exec($ch);
        $errno = curl_errno($ch);
        $errmsg = curl_error($ch);

        if ($errno) {
            throw new HttpClientException(sprintf(self::ERROR_CURL, $errno, $errmsg));
        }

        $header = curl_getinfo($ch);
        $this->statusCode = $header['http_code'];
        $this->requestSize = $header['request_size'];
        $this->content = $content;

        curl_close($ch);
    }

    /**
     * return the content
     *
     * @return string
     * @throws HttpClientException
     */
    public function getContent()
    {
        $this->checkSent();

        return $this->content;
    }

    /**
     * get status code
     *
     * @return int
     * @throws HttpClientException
     */
    public function getStatusCode()
    {
        $this->checkSent();

        return $this->statusCode;
    }

    /**
     * check if request has been made
     *
     * @throws HttpClientException
     */
    private function checkSent()
    {
        if ($this->content === null) { // might be better using a 'sent' flag here ..
            throw new HttpClientException(self::ERROR_NO_REQUEST_SENT);
        }
    }

    /**
     * create and return curl resource
     *
     * @return resource
     */
    private function initCurl()
    {
        $ch = curl_init($this->url);

        curl_setopt_array($ch, [
            CURLOPT_URL => $this->url,
            CURLOPT_POST => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_COOKIESESSION => true,
            CURLOPT_FOLLOWLOCATION => true,
        ]);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Encoding: none',
            'Accept-Language: en-US,en;q=0.8,hu;q=0.6',
            'Cache-Control: no-cache',
            'Connection: close',
            'Cookie: ' . file_get_contents(__DIR__ . self::COOKIE_FILE),
            'Host: www.sainsburys.co.uk',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36',
        ]);

        return $ch;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
