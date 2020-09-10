<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Responses\Extensions\Sidn;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Extensions\Responses\Sidn\SidnDomainInfoResponse;
use SandwaveIo\EppClient\Epp\Rfc\Document;

class SidnDomainInfoResponseTest extends TestCase
{
    public function test_response(): void
    {
        $response = new SidnDomainInfoResponse(Document::fromString((string) file_get_contents(__DIR__ . '/../../../data/responses/domain_info_sidn.xml')));

        $this->assertTrue($response->isSuccess(), 'Failed asserting that response is successful.');
        $this->assertSame('1000', (string) $response->getResultCode(), 'Failed assertion on response code.');
        $this->assertSame('Command completed successfully', $response->getResultMessage(), 'Failed assertion on response text.');

        $this->assertSame('ABC-12345', $response->getClientTransactionIdentifier(), 'Failed assertion on clTRID text.');
        $this->assertSame('54322-XYZ', $response->getServerTransactionIdentifier(), 'Failed assertion on svTRID text.');

        $this->assertSame('example.com', $response->getDomainName(), 'Failed assertion on domain name');
        $this->assertSame('1', $response->getDomainPeriod(), 'Failed assertion on domain period');
        $this->assertSame('??', $response->getDomainOptout(), 'Failed assertion on domain optout');
        $this->assertSame('??', $response->getDomainLimited(), 'Failed assertion on domain limited');
    }
}
