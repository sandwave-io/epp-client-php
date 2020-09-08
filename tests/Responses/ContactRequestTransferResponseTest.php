<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Responses;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Responses\ContactRequestTransferResponse;

class ContactRequestTransferResponseTest extends TestCase
{
    public function test_response(): void
    {
        $response = new ContactRequestTransferResponse(Document::fromString((string) file_get_contents(__DIR__ . '/../data/responses/contact_request_transfer.xml')));

        $this->assertTrue($response->isSuccess(), 'Failed asserting that response is successful.');
        $this->assertSame('1001', (string) $response->getResultCode(), 'Failed assertion on response code.');
        $this->assertSame('Command completed successfully; action pending', $response->getResultMessage(), 'Failed assertion on response text.');

        $this->assertSame('ABC-12345', $response->getClientTransactionIdentifier(), 'Failed assertion on clTRID text.');
        $this->assertSame('54322-XYZ', $response->getServerTransactionIdentifier(), 'Failed assertion on svTRID text.');

        $this->assertSame('sh8013', $response->getContactId());
        $this->assertSame('pending', $response->getTransferStatus());
        $this->assertSame('ClientX', $response->getRequestClientId());
        $this->assertInstanceOf(Carbon::class, $response->getRequestDate());
        $this->assertSame('ClientY', $response->getAcceptanceClientId());
        $this->assertInstanceOf(Carbon::class, $response->getAcceptanceDate());
    }
}
