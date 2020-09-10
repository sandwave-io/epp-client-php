<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainInfoRequest;

class DomainInfoRequestTest extends TestCase
{
    public function test_domain_info_request(): void
    {
        $request = new DomainInfoRequest(
            'example.com',
            DomainInfoRequest::FILTER_ALL,
            null
        );

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_info.xml', $xmlString);
    }

    public function test_domain_info_password_request(): void
    {
        $request = new DomainInfoRequest(
            'example.com',
            DomainInfoRequest::FILTER_ALL,
            '2fooBAR'
        );

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_info_password.xml', $xmlString);
    }

    public function test_domain_info_sidn_request(): void
    {
        $request = new DomainInfoRequest(
            'example.com',
            DomainInfoRequest::FILTER_ALL,
            null
        );

        $request->addEppNamespace('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_info_sidn.xml', $xmlString);
    }
}
