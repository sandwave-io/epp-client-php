<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Responses;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainCheckResponse;

class DomainCheckResponseTest extends TestCase
{
    public function test_response(): void
    {
        $response = new DomainCheckResponse(Document::fromString((string) file_get_contents(__DIR__ . '/../data/responses/domain_check.xml')));

        $this->assertTrue($response->isSuccess(), 'Failed asserting that response is successful.');
        $this->assertSame('1000', (string) $response->getResultCode(), 'Failed assertion on response code.');
        $this->assertSame('Command completed successfully', $response->getResultMessage(), 'Failed assertion on response text.');

        $this->assertSame('ABC-12345', $response->getClientTransactionIdentifier(), 'Failed assertion on clTRID text.');
        $this->assertSame('54322-XYZ', $response->getServerTransactionIdentifier(), 'Failed assertion on svTRID text.');

        $checkData = $response->getCheckData();

        $dotComData = $checkData[0];
        $this->assertSame('example.com', $dotComData->domain);
        $this->assertTrue($dotComData->isAvailable);
        $this->assertNull($dotComData->reason);

        $dotNetData = $checkData[1];
        $this->assertSame('example.net', $dotNetData->domain);
        $this->assertFalse($dotNetData->isAvailable);
        $this->assertSame('In use', $dotNetData->reason);

        $dotOrgData = $checkData[2];
        $this->assertSame('example.org', $dotOrgData->domain);
        $this->assertTrue($dotOrgData->isAvailable);
        $this->assertNull($dotOrgData->reason);
    }
}
