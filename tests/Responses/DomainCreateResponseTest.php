<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Responses;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainCreateResponse;

class DomainCreateResponseTest extends TestCase
{
    public function test_response(): void
    {
        $response = new DomainCreateResponse(Document::fromString((string) file_get_contents(__DIR__ . '/../data/responses/domain_create.xml')));

        $this->assertTrue($response->isSuccess(), 'Failed asserting that response is successful.');
        $this->assertSame('1000', (string) $response->getResultCode(), 'Failed assertion on response code.');
        $this->assertSame('Command completed successfully', $response->getResultMessage(), 'Failed assertion on response text.');

        $this->assertSame('ABC-12345', $response->getClientTransactionIdentifier(), 'Failed assertion on clTRID text.');
        $this->assertSame('54321-XYZ', $response->getServerTransactionIdentifier(), 'Failed assertion on svTRID text.');

        $this->assertSame('example.com', $response->getDomainName());
        $this->assertInstanceOf(Carbon::class, $response->getCreatedDate());
        $this->assertInstanceOf(Carbon::class, $response->getExpiryDate());
    }
}
