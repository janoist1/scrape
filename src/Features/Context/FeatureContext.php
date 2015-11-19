<?php

namespace Sainsburys\TechTest\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Sainsburys\TechTest\TechTest;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends \PHPUnit_Framework_TestCase implements Context, SnippetAcceptingContext
{
    /** @var TechTest */
    private $app;

    /** @var CommandTester */
    private $tester;

    /** @var array */
    private $responseHTMLs;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $mockHttpClientProvider;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->setupMockHttpClientProvider();

        $this->app = new TechTest($this->mockHttpClientProvider);
        $this->responseHTMLs = [];
    }

    /**
     * @When /^I run "([^"]*)" command$/
     *
     * @param string $name - name of the command
     */
    public function iRunCommand($name)
    {
        $this->runCommand($name);
    }

    /**
     * @Given the following html response contents:
     *
     * @param TableNode $table
     */
    public function theFollowingHtmlResponses(TableNode $table)
    {
        foreach ($table->getHash() as $item) {
            $this->responseHTMLs[] = file_get_contents(__DIR__ . '/../../Resources/Fixtures/' . $item['content']);
        }

        $this->buildMockHttpClientProvider();
    }

    /**
     * @Then I should see the following JSON:
     *
     * @param PyStringNode $string
     * @throws \Exception
     */
    public function iShouldSeeTheFollowingJson(PyStringNode $string)
    {
        $expectedData = json_decode($string, true);
        $actualData = json_decode($this->tester->getDisplay(), true);

        if ($actualData !== $expectedData) {
            throw new \Exception("the expected data was:\n$string\n\nbut the returned data was:\n" . $this->tester->getDisplay());
        }
    }

    /**
     * Run a given command with the given options
     *
     * @param string $name
     * @param array $options
     */
    private function runCommand($name, array $options = [])
    {
        $command = $this->app->find($name);
        $this->tester = new CommandTester($command);
        $this->tester->execute(['command' => $command->getName()], $options);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function setupMockHttpClientProvider()
    {
        $this->mockHttpClientProvider = $this
            ->getMockBuilder('Sainsburys\TechTest\HttpClient\Provider\HttpClientProviderInterface')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Assemble the mack used by the ScrapeService
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function buildMockHttpClientProvider()
    {
        foreach ($this->responseHTMLs as $i => $html) {
            // mock the client
            $httpClient = $this
                ->getMockBuilder('Sainsburys\TechTest\HttpClient\HttpClientInterface')
                ->disableOriginalConstructor()
                ->getMock();
            $httpClient
                ->expects($this->once())
                ->method('sendRequest');
            $httpClient
                ->expects($this->once())
                ->method('getStatusCode')
                ->will($this->returnValue(200));
            $httpClient
                ->expects($this->once())
                ->method('getContent')
                ->will($this->returnValue($html));

            // set up the provider
            $this->mockHttpClientProvider
                ->expects($this->at($i))
                ->method('provideClient')
                ->will($this->returnValue($httpClient));
        }
    }
}
