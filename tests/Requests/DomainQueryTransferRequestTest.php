<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainQueryTransferRequest;

class DomainQueryTransferRequestTest extends TestCase
{
    public function test_domain_transfer_request(): void
    {
        $request = new DomainQueryTransferRequest('example.com');
        $request->addEppNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $request->addEppNamespace('contact', 'urn:ietf:params:xml:ns:contact-1.0');

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_transfer.xml', $xmlString);
    }

    public function test_domain_transfer_password_request(): void
    {
        $request = new DomainQueryTransferRequest('example.com', '2fooBAR');
        $request->addEppNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $request->addEppNamespace('contact', 'urn:ietf:params:xml:ns:contact-1.0');

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_transfer_password.xml', $xmlString);
    }

    public function test_domain_transfer_password_roid_request(): void
    {
        $request = new DomainQueryTransferRequest('example.com', '2fooBAR', 'JD1234-REP');
        $request->addEppNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $request->addEppNamespace('contact', 'urn:ietf:params:xml:ns:contact-1.0');

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_transfer_password_roid.xml', $xmlString);
    }

    public function test_domain_transfer_sidn_request(): void
    {
        $request = new DomainQueryTransferRequest('example.com');
        $request->addEppNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $request->addEppNamespace('contact', 'urn:ietf:params:xml:ns:contact-1.0');

        $request->addEppNamespace('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_transfer_sidn.xml', $xmlString);
    }
}
