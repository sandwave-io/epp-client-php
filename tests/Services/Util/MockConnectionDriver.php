<?php


namespace SandwaveIo\EppClient\Tests\Services\Util;


use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\ConnectionDriver\AbstractConnectionDriver;
use Webmozart\Assert\Assert;

final class MockConnectionDriver extends AbstractConnectionDriver
{
    /** @var TestCase */
    private $testCase;

    /** @var array<int, array<string, string>> */
    private $assertions = [];

    public function __construct(TestCase $testCase)
    {
        parent::__construct('test.example.com', '12345');

        $this->testCase = $testCase;
    }

    public function __destruct()
    {
        foreach ($this->assertions as $assertion) {
            $requestPath = $assertion['request_path'];
            $responsePath = $assertion['response_path'];
            $this->testCase->assertTrue(
                false,
                "A request was never executed! Request with body: {$requestPath} (and response: {$responsePath})."
            );
        }
    }

    public function connect(): bool
    {
        return true;
    }

    public function disconnect(): bool
    {
        return false;
    }

    public function executeRequest(string $request, string $requestId): string
    {
        if (! $assertion = $this->nextAssertion()) {
            return "";
        }

        $this->testCase->assertXmlStringEqualsXmlFile($assertion['request_path'], $request);

        return file_get_contents($assertion['response_path']);
    }

    public function expectRequest(string $requestPath, string $responsePath): MockConnectionDriver
    {
        Assert::fileExists($requestPath, 'Request path must exist');
        Assert::fileExists($responsePath, 'Response path must exist');

        $this->assertions[] = [
            'request_path' => $requestPath,
            'response_path' => $responsePath,
        ];

        return $this;
    }

    /**
     * @return array<string,string>|null
     */
    private function nextAssertion(): ?array
    {
        $key = array_key_first($this->assertions);
        if (is_null($key)) {
            return null;
        }

        $assertion = $this->assertions[$key];
        unset($this->assertions[$key]);

        return $assertion;
    }
}