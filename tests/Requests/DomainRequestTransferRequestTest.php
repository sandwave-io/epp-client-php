<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainRequestTransferRequest;

class DomainRequestTransferRequestTest extends TestCase
{
    public function test_domain_request_transfer_request(): void
    {
        $request = new DomainRequestTransferRequest(
            'example.com',
            '2fooBAR',
            'JD1234-REP',
            1
        );

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_request_transfer.xml', $xmlString);
    }

    public function test_domain_request_transfer_sidn_request(): void
    {
        $request = new DomainRequestTransferRequest(
            'example.com',
            '2fooBAR',
            'JD1234-REP',
            1
        );

        $request->addEppNamespace('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_request_transfer_sidn.xml', $xmlString);
    }
}
