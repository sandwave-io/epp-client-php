<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Responses;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainRenewResponse;

class DomainRenewResponseTest extends TestCase
{
    public function test_response(): void
    {
        $response = new DomainRenewResponse(Document::fromString((string) file_get_contents(__DIR__ . '/../data/responses/domain_renew.xml')));

        $this->assertTrue($response->isSuccess(), 'Failed asserting that response is successful.');
        $this->assertSame('1000', (string) $response->getResultCode(), 'Failed assertion on response code.');
        $this->assertSame('Command completed successfully', $response->getResultMessage(), 'Failed assertion on response text.');

        $this->assertSame('ABC-12345', $response->getClientTransactionIdentifier(), 'Failed assertion on clTRID text.');
        $this->assertSame('54322-XYZ', $response->getServerTransactionIdentifier(), 'Failed assertion on svTRID text.');

        $this->assertSame('example.com', $response->getDomainName());
        $this->assertInstanceOf(Carbon::class, $response->getExpiryDate());
    }
}
