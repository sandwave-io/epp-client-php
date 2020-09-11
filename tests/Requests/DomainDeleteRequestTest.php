<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainDeleteRequest;

class DomainDeleteRequestTest extends TestCase
{
    public function test_domain_delete_request(): void
    {
        $request = new DomainDeleteRequest('example.com');
        $request->addEppNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $request->addEppNamespace('contact', 'urn:ietf:params:xml:ns:contact-1.0');

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_delete.xml', $xmlString);
    }

    public function test_domain_delete_sidn_request(): void
    {
        $request = new DomainDeleteRequest('example.com');
        $request->addEppNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $request->addEppNamespace('contact', 'urn:ietf:params:xml:ns:contact-1.0');

        $request->addEppNamespace('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_delete_sidn.xml', $xmlString);
    }
}
