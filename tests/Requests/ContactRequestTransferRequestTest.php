<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Contact\ContactRequestTransferRequest;

class ContactRequestTransferRequestTest extends TestCase
{

    public function test_contact_transfer_password_request(): void
    {
        $request = new ContactRequestTransferRequest('sh8013', '2fooBAR');

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/contact_request_transfer.xml', $xmlString);
    }

    public function test_contact_transfer_sidn_request(): void
    {
        $request = new ContactRequestTransferRequest('sh8013', '2fooBAR');

        $request->addEppExtension('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/contact_request_transfer_sidn.xml', $xmlString);
    }
}
