<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Responses;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Responses\ContactInfoResponse;

class ContactInfoResponseTest extends TestCase
{
    public function test_response(): void
    {
        $response = new ContactInfoResponse(Document::fromString((string) file_get_contents(__DIR__ . '/../data/responses/contact_info.xml')));

        $this->assertTrue($response->isSuccess(), 'Failed asserting that response is successful.');
        $this->assertSame('1000', (string) $response->getResultCode(), 'Failed assertion on response code.');
        $this->assertSame('Command completed successfully', $response->getResultMessage(), 'Failed assertion on response text.');

        $this->assertSame('ABC-12345', $response->getClientTransactionIdentifier(), 'Failed assertion on clTRID text.');
        $this->assertSame('54322-XYZ', $response->getServerTransactionIdentifier(), 'Failed assertion on svTRID text.');

        $this->assertSame('sh8013', $response->getContactId());
        $this->assertSame('2fooBAR', $response->getPassword());

        $this->assertSame('SH8013-REP', $response->getRepositoryObjectIdentifier());
        $this->assertSame(['linked', 'clientDeleteProhibited'], $response->getStatuses());

        // TODO: Add contact assertions

        $this->assertSame('ClientY', $response->getClientId());
        $this->assertSame('ClientX', $response->getCreationClientId());
        $this->assertSame('ClientX', $response->getLastUpdateClientId());

        $this->assertInstanceOf(Carbon::class, $response->getCreatedDate());
        $this->assertInstanceOf(Carbon::class, $response->getUpdatedDate());
        $this->assertInstanceOf(Carbon::class, $response->getTransferedDate());

        $this->assertSame('2fooBAR', $response->getPassword());
    }
}
