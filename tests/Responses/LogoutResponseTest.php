<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Responses;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Responses\LogoutResponse;

class LogoutResponseTest extends TestCase
{
    public function test_response(): void
    {
        $response = new LogoutResponse(Document::fromString((string) file_get_contents(__DIR__ . '/../data/responses/logout.xml')));

        $this->assertTrue($response->isSuccess(), 'Failed asserting that response is successful.');
        $this->assertSame('1500', (string) $response->getResultCode(), 'Failed assertion on response code.');
        $this->assertSame('Command completed successfully; ending session', $response->getResultMessage(), 'Failed assertion on response text.');

        $this->assertSame('ABC-12345', $response->getClientTransactionIdentifier(), 'Failed assertion on clTRID text.');
        $this->assertSame('54321-XYZ', $response->getServerTransactionIdentifier(), 'Failed assertion on svTRID text.');
    }
}
