<?php


namespace SandwaveIo\EppClient\Tests\Responses\Extensions\Sidn;


use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Extensions\Responses\Sidn\SidnDomainQueryTransferResponse;
use SandwaveIo\EppClient\Epp\Rfc\Document;

class SidnDomainQueryTransferResponseTest extends TestCase
{
    public function test_response(): void
    {
        $response = new SidnDomainQueryTransferResponse(Document::fromString((string) file_get_contents(__DIR__.'/../../../data/responses/domain_transfer_sidn.xml')));

        $this->assertTrue($response->isSuccess(), 'Failed asserting that response is successful.');
        $this->assertSame('1000', (string) $response->getResultCode(), 'Failed assertion on response code.');
        $this->assertSame('Command completed successfully', $response->getResultMessage(), 'Failed assertion on response text.');

        $this->assertSame('ABC-12345', $response->getClientTransactionIdentifier(), 'Failed assertion on clTRID text.');
        $this->assertSame('54322-XYZ', $response->getServerTransactionIdentifier(), 'Failed assertion on svTRID text.');

        $this->assertSame('example.com', $response->getDomainName());
        $this->assertSame('pending', $response->getTransferStatus());
        $this->assertSame('ClientX', $response->getRequestClientId());
        $this->assertInstanceOf(Carbon::class, $response->getRequestDate());
        $this->assertSame('ClientY', $response->getAcceptanceClientId());
        $this->assertInstanceOf(Carbon::class, $response->getAcceptanceDate());
        $this->assertInstanceOf(Carbon::class, $response->getExpiryDate());

        //sidn
        $this->assertSame('domain:transfer', $response->getPolledCommand(), 'Failed asserting on correct command in response.');
        $this->assertSame('1000', (string) $response->getPolledResultCode(), 'Failed assertion on response code.');
        $this->assertSame('The domain name has been transferred.', $response->getPolledResultMessage(), 'Failed assertion on response text');
        $this->assertSame('testmetaregistrar1.nl', $response->getPolledDomainname(), 'Failed assertion on response domain name.');
        $this->assertSame('serverApproved', $response->getPolledTransferStatus(), 'Failed assertion on response transaction status');
        $this->assertSame('GEENP', $response->getPolledRequestClientId(), 'Failed assertion on response request client id');
        $this->assertSame('2011-11-16T16:22:57.000Z', $response->getPolledRequestDate(), 'Failed assertion on response request date');
        $this->assertSame('X1837', $response->getPolledActionClientId(), 'Failed assertion on response action client id');
        $this->assertSame('2011-11-16T16:22:57.941Z', $response->getPolledActionDate(), 'Failed assertion on response action date');
        $this->assertSame('41910644', $response->getPolledTransactionId(), 'Failed assertion on response transaction id');
    }
}