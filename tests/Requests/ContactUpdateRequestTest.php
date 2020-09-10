<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Contact\ContactUpdateRequest;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Objects\ContactPostalInfo;

class ContactUpdateRequestTest extends TestCase
{
    public function test_contact_update_request(): void
    {
        $request = new ContactUpdateRequest(
            'sh8013',
            ['clientDeleteProhibited'],
            null,
            '2fooBAR',
            new ContactPostalInfo(
                null,
                'Dulles',
                'US',
                '',
                '124 Example Dr.',
                'Suite 200',
                null,
                'VA',
                '20166-6503'
            ),
            null,
            '+1.7034444444',
            '',
            [
                ContactUpdateRequest::DISCLOSE_VOICE,
                ContactUpdateRequest::DISCLOSE_EMAIL,
            ],
            true
        );

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/contact_update.xml', $xmlString);
    }

    public function test_contact_update_sidn_request(): void
    {
        $request = new ContactUpdateRequest(
            'sh8013',
            ['clientDeleteProhibited'],
            null,
            '2fooBAR',
            new ContactPostalInfo(
                null,
                'Dulles',
                'US',
                '',
                '124 Example Dr.',
                'Suite 200',
                null,
                'VA',
                '20166-6503'
            ),
            null,
            '+1.7034444444',
            '',
            [
                ContactUpdateRequest::DISCLOSE_VOICE,
                ContactUpdateRequest::DISCLOSE_EMAIL,
            ],
            true
        );

        $request->addEppNamespace('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/contact_update_sidn.xml', $xmlString);
    }
}
