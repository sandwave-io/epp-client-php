<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\DomainInfoRequest;

class DomainInfoRequestTest extends TestCase
{
    public function test_domain_info_request(): void
    {
        $request = new DomainInfoRequest(
            'example.com',
            DomainInfoRequest::FILTER_ALL,
            [],
            'ABCD-1234'
        );

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/data/domain_info.xml', $xmlString);
    }

    public function test_domain_info_sidn_request(): void
    {
        $request = new DomainInfoRequest(
            'example.com',
            DomainInfoRequest::FILTER_ALL,
            ['sidn-ext-epp' => 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0'],
            'ABCD-1234'
        );

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/data/domain_info_sidn.xml', $xmlString);
    }
}
