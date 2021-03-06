<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Responses\Extensions\Sidn;

use Carbon\Carbon;
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

        $this->assertSame('example.com', $response->getDomainName());
        $this->assertSame('EXAMPLE1-REP', $response->getRepositoryObjectIdentifier());
        $this->assertSame(['ok'], $response->getStatuses());

        $this->assertSame(['jd1234'], $response->getRegistrants());
        $this->assertSame([
            'admin' => 'sh8013',
            'tech' => 'sh8013',
        ], $response->getContacts());

        $this->assertSame([
            'ns1.example.com',
            'ns1.example.net',
        ], $response->getNameServers());
        $this->assertSame([
            'ns1.example.com',
            'ns2.example.com',
        ], $response->getHosts());

        $this->assertSame('ClientX', $response->getClientId());
        $this->assertSame('ClientY', $response->getCreationClientId());
        $this->assertSame('ClientX', $response->getLastUpdateClientId());

        $this->assertInstanceOf(Carbon::class, $response->getCreatedDate());
        $this->assertInstanceOf(Carbon::class, $response->getUpdatedDate());
        $this->assertInstanceOf(Carbon::class, $response->getExpirationDate());
        $this->assertInstanceOf(Carbon::class, $response->getTransferedDate());

        $this->assertSame('2fooBAR', $response->getPassword());

        //sidn
        $this->assertSame('example.com', $response->getDomainName(), 'Failed assertion on domain name');
        $this->assertSame('1', $response->getDomainPeriod(), 'Failed assertion on domain period');
        $this->assertSame('??', $response->getDomainOptout(), 'Failed assertion on domain optout');
        $this->assertSame('??', $response->getDomainLimited(), 'Failed assertion on domain limited');
    }
}
