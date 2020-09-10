<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainUpdateRequest;

class DomainUpdateRequestTest extends TestCase
{
    public function test_domain_update_request(): void
    {
        $request = new DomainUpdateRequest(
            'example.com',
            ['ns2.example.com'],
            ['tech' => 'mak21'],
            ['clientHold' => 'Payment overdue.'],
            ['ns1.example.com'],
            ['tech' => 'sh8013'],
            ['clientUpdateProhibited' => ''],
            'sh8013',
            '2BARfoo'
        );

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_update.xml', $xmlString);
    }

    public function test_domain_update_sidn_request(): void
    {
        $request = new DomainUpdateRequest(
            'example.com',
            ['ns2.example.com'],
            ['tech' => 'mak21'],
            ['clientHold' => 'Payment overdue.'],
            ['ns1.example.com'],
            ['tech' => 'sh8013'],
            ['clientUpdateProhibited' => ''],
            'sh8013',
            '2BARfoo'
        );

        $request->addEppNamespace('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_update_sidn.xml', $xmlString);
    }
}
