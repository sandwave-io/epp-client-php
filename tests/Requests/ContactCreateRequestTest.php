<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Extensions\Requests\Sidn\SidnContactCreateRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Contact\ContactCreateRequest;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Objects\ContactPostalInfo;

class ContactCreateRequestTest extends TestCase
{
    public function test_contact_create_request(): void
    {
        $request = new ContactCreateRequest(
            'sh8013',
            'jdoe@example.com',
            '2fooBAR',
            new ContactPostalInfo(
                'John Doe',
                'Dulles',
                'US',
                'Example Inc.',
                '123 Example Dr.',
                'Suite 100',
                null,
                'VA',
                '20166-6503'
            ),
            null,
            '+1.7035555555',
            '+1.7035555556',
            [
                ContactCreateRequest::DISCLOSE_VOICE,
                ContactCreateRequest::DISCLOSE_EMAIL,
            ],
            false
        );
        $request->addEppNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $request->addEppNamespace('contact', 'urn:ietf:params:xml:ns:contact-1.0');

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/contact_create.xml', $xmlString);
    }

    public function test_contact_create_sidn_request(): void
    {
        $request = new SidnContactCreateRequest(
            'sh8013',
            'jdoe@example.com',
            '2fooBAR',
            new ContactPostalInfo(
                'John Doe',
                'Dulles',
                'US',
                'Example Inc.',
                '123 Example Dr.',
                'Suite 100',
                null,
                'VA',
                '20166-6503'
            ),
            null,
            '+1.7035555555',
            '+1.7035555556',
            [
                ContactCreateRequest::DISCLOSE_VOICE,
                ContactCreateRequest::DISCLOSE_EMAIL,
            ],
            false
        );
        $request->addEppNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $request->addEppNamespace('contact', 'urn:ietf:params:xml:ns:contact-1.0');

        $request->addEppNamespace('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/contact_create_business_sidn.xml', $xmlString);
    }
}
