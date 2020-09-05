<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\DomainCheckRequest;

class DomainCheckRequestTest extends TestCase
{
    public function test_domain_check_request(): void
    {
        $request = new DomainCheckRequest([
            'example.com',
            'example.net',
            'example.org',
        ]);

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_check.xml', $xmlString);
    }

    public function test_domain_check_sidn_request(): void
    {
        $request = new DomainCheckRequest([
            'example.com',
            'example.net',
            'example.org',
        ]);

        $request->addEppExtension('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_check_sidn.xml', $xmlString);
    }
}
