<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Contact\ContactCheckRequest;

class ContactCheckRequestTest extends TestCase
{
    public function test_contact_check_request(): void
    {
        $request = new ContactCheckRequest([
            'sh8013',
            'sah8013',
            '8013sah',
        ]);

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/contact_check.xml', $xmlString);
    }

    public function test_contact_check_sidn_request(): void
    {
        $request = new ContactCheckRequest([
            'sh8013',
            'sah8013',
            '8013sah',
        ]);

        $request->addEppNamespace('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/contact_check_sidn.xml', $xmlString);
    }
}
