<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainCreateRequest;

class DomainCreateRequestTest extends TestCase
{
    public function test_domain_create_request(): void
    {
        $request = new DomainCreateRequest(
            'example.com',
            2,
            ['ns1.example.net', 'ns2.example.net'],
            'jd1234',
            [
                'admin' => 'sh8013',
                'tech' => 'sh8013',
            ],
            '2fooBAR'
        );

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_create.xml', $xmlString);
    }

    public function test_domain_create_sidn_request(): void
    {
        $request = new DomainCreateRequest(
            'example.com',
            2,
            ['ns1.example.net', 'ns2.example.net'],
            'jd1234',
            [
                'admin' => 'sh8013',
                'tech' => 'sh8013',
            ],
            '2fooBAR'
        );

        $request->addEppNamespace('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_create_sidn.xml', $xmlString);
    }
}
