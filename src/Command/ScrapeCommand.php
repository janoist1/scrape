<?php

namespace Sainsburys\TechTest\Command;

use Sainsburys\TechTest\Service\ScrapeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ScrapeCommand
 *
 * @package Sainsburys\TechTest\Console\Command
 */
class ScrapeCommand extends Command
{
    const RIPE_FRUITS_URL = 'http://www.sainsburys.co.uk/webapp/wcs/stores/servlet/CategoryDisplay?listView=true&orderBy=FAVOURITES_FIRST&parent_category_rn=12518&top_category=12518&langId=44&beginIndex=0&pageSize=20&catalogId=10137&searchTerm=&categoryId=185749&listId=&storeId=10151&promotionId=#langId=44&storeId=10151&catalogId=10137&categoryId=185749&parent_category_rn=12518&top_category=12518&pageSize=20&orderBy=FAVOURITES_FIRST&searchTerm=&beginIndex=0&hideFilters=true';

    /** @var ScrapeService */
    private $scrapeService;

    /**
     * construct - requires ScrapeService as a dependency
     *
     * @param ScrapeService $scrapeService
     */
    public function __construct(ScrapeService $scrapeService)
    {
        parent::__construct();

        $this->scrapeService = $scrapeService;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('tech-test:scrape')
            ->setDescription('Scrape the Sainsbury\'s grocery site - Ripe Fruits page')
            ->addOption(
                'url',
                null,
                InputOption::VALUE_REQUIRED,
                'URL of the Ripe Fruits page to use as a source'
            )
        ;
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getOption('url');
        $json = $this->scrapeService->scrapeUrl($url ?: self::RIPE_FRUITS_URL);

        $output->writeln($json);
    }
}
