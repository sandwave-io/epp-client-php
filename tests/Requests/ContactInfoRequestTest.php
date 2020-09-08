<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Contact\ContactInfoRequest;

class ContactInfoRequestTest extends TestCase
{
    public function test_contact_info_request(): void
    {
        $request = new ContactInfoRequest(
            'sh8013',
            null
        );

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/contact_info.xml', $xmlString);
    }

    public function test_contact_info_password_request(): void
    {
        $request = new ContactInfoRequest(
            'sh8013',
            '2fooBAR'
        );

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/contact_info_password.xml', $xmlString);
    }

    public function test_contact_info_sidn_request(): void
    {
        $request = new ContactInfoRequest(
            'sh8013',
            null
        );

        $request->addEppExtension('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/contact_info_sidn.xml', $xmlString);
    }
}
