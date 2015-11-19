<?php

namespace Sainsburys\TechTest;

use Sainsburys\TechTest\HttpClient\Provider\HttpClientProvider;
use Sainsburys\TechTest\HttpClient\Provider\HttpClientProviderInterface;
use Symfony\Component\Console\Application;
use Sainsburys\TechTest\Service\ScrapeService;
use Sainsburys\TechTest\Command\ScrapeCommand;


/**
 * Class TechTest - wrap the application, tie things together
 *
 * @package Sainsburys\TechTest
 */
class TechTest extends Application
{
    const APP_NAME = 'TechTest';
    const APP_VERSION = '1.0';

    /**
     * constructor - inject HttpClientProvider if necessary (ex. for testing)
     *
     * @param HttpClientProviderInterface $httpClientProvider
     */
    public function __construct(HttpClientProviderInterface $httpClientProvider = null)
    {
        parent::__construct(self::APP_NAME, self::APP_VERSION);

        if ($httpClientProvider === null) {
            $httpClientProvider = new HttpClientProvider();
        }

        $this->add(new ScrapeCommand(new ScrapeService($httpClientProvider)));
    }
}
