<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Responses;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Responses\ContactCheckResponse;

class ContactCheckResponseTest extends TestCase
{
    public function test_response(): void
    {
        $response = new ContactCheckResponse(Document::fromString((string) file_get_contents(__DIR__ . '/../data/responses/contact_check.xml')));

        $this->assertTrue($response->isSuccess(), 'Failed asserting that response is successful.');
        $this->assertSame('1000', (string) $response->getResultCode(), 'Failed assertion on response code.');
        $this->assertSame('Command completed successfully', $response->getResultMessage(), 'Failed assertion on response text.');

        $this->assertSame('ABC-12345', $response->getClientTransactionIdentifier(), 'Failed assertion on clTRID text.');
        $this->assertSame('54322-XYZ', $response->getServerTransactionIdentifier(), 'Failed assertion on svTRID text.');

        $checkData = $response->getCheckData();

        $dotComData = $checkData[0];
        $this->assertSame('sh8013', $dotComData->contact);
        $this->assertTrue($dotComData->isAvailable);
        $this->assertNull($dotComData->reason);

        $dotNetData = $checkData[1];
        $this->assertSame('sah8013', $dotNetData->contact);
        $this->assertFalse($dotNetData->isAvailable);
        $this->assertSame('In use', $dotNetData->reason);

        $dotOrgData = $checkData[2];
        $this->assertSame('8013sah', $dotOrgData->contact);
        $this->assertTrue($dotOrgData->isAvailable);
        $this->assertNull($dotOrgData->reason);
    }
}
