<?php

namespace Sainsburys\TechTest\Service;

use Sainsburys\TechTest\Model\ResultSet;
use Symfony\Component\DomCrawler\Crawler;
use Sainsburys\TechTest\Exception\HttpClientException;
use Sainsburys\TechTest\Exception\ScrapeException;
use Sainsburys\TechTest\HttpClient\Provider\HttpClientProviderInterface;
use Sainsburys\TechTest\HttpClient\HttpClientInterface;
use Sainsburys\TechTest\Model\Result;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;


/**
 * Class ScrapeService - main logic
 */
class ScrapeService
{
    // my eyes prefer CSS selectors
    const CSS_SELECTOR_PRODUCT = '#productLister > ul > li > div.product > div.productInner';
    const CSS_SELECTOR_LINK = 'div.productInfoWrapper > div > h3 > a';
    const CSS_SELECTOR_TITLE = 'div.productInfoWrapper > div > h3 > a';
    const CSS_SELECTOR_UNIT_PRICE = 'div.addToTrolleytabBox > div > div > div > div.pricing > p.pricePerUnit';
    const CSS_SELECTOR_DESCRIPTION = '#information > productcontent > htmlcontent > div.productText > p:nth-child(1)';
    const REQUEST_ERROR = 'Request error: %s';
    const REQUEST_WRONG_STATUS_CODE = 'Wrong status code';

    /** @var HttpClientProviderInterface */
    private $httpClientProvider;

    /**
     * constructor - depends on HttpClientProvider
     *
     * @param HttpClientProviderInterface $httpClientProvider
     */
    function __construct(HttpClientProviderInterface $httpClientProvider)
    {
        $this->httpClientProvider = $httpClientProvider;
    }

    /**
     * Scrape a content of a given url
     *
     * @param string $url - feed url
     * @return array
     * @throws ScrapeException
     */
    public function scrapeUrl($url)
    {
        $html = $this->fetchPageContent($url);
        $results = $this->parseHTML($html);

        // return a JSON data
        return $this->serializeResults($results);
    }

    /**
     * parse the given HTML and return set of results
     *
     * @param $html
     * @return ResultSet
     * @throws ScrapeException
     */
    private function parseHTML($html)
    {
        $results = new ResultSet();
        $crawler = new Crawler($html);

        $crawler
            ->filter(self::CSS_SELECTOR_PRODUCT)
            ->each(function (Crawler $productNode, $i) use (&$results) {
                try {
                    $titleNode = $productNode->filter(self::CSS_SELECTOR_TITLE);

                    // extract title
                    $title = trim($titleNode->text());

                    // get the url
                    $url = $titleNode->filterXPath('//a/@href')->text();

                    // get products detailed page
                    $html = $this->fetchPageContent($url);

                    // extract price
                    $pricePerUnitText = $productNode->filter(self::CSS_SELECTOR_UNIT_PRICE)->filterXPath('//text()')->text();
                    $unitPrice = filter_var($pricePerUnitText, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                    // size in kb
                    $size = round(strlen($html) / 1024, 1);

                    // description
                    $crawler = new Crawler($html);
                    $description = $crawler->filter(self::CSS_SELECTOR_DESCRIPTION)->text();

                    $results->addResult(new Result($title, $size, $unitPrice, $description));
                } catch (\InvalidArgumentException $e) {
                    // simply skip this item
                }
            });

        return $results;
    }

    /**
     * return a JSON representation of the given set of results
     *
     * @param ResultSet $resultSet
     * @return array
     */
    private function serializeResults(ResultSet $resultSet)
    {
        // set up the serializer
        $encoder = new JsonEncoder();
        $normalizer = new GetSetMethodNormalizer();
        $serializer = new Serializer([$normalizer], [$encoder]);

        return $serializer->serialize($resultSet, 'json');
    }

    /**
     * fire a request and return its result content
     *
     * @param $url
     * @return string
     * @throws HttpClientException
     * @throws ScrapeException
     */
    private function fetchPageContent($url)
    {
        // get a http client
        $httpClient = $this->httpClientProvider->provideCurlClient($url);

        try {
            $httpClient->sendRequest();
        } catch (HttpClientException $e) {
            // convert the exception
            throw new ScrapeException(sprintf(self::REQUEST_ERROR, $e->getMessage()));
        }

        // throw exception when status is not OK
        if ($httpClient->getStatusCode() != 200) {
            throw new ScrapeException(self::REQUEST_WRONG_STATUS_CODE);
        }

        return $httpClient->getContent();
    }
}
