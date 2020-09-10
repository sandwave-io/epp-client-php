<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Contact\ContactDeleteRequest;

class ContactDeleteRequestTest extends TestCase
{
    public function test_contact_delete_request(): void
    {
        $request = new ContactDeleteRequest('sh8013');

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/contact_delete.xml', $xmlString);
    }

    public function test_contact_delete_sidn_request(): void
    {
        $request = new ContactDeleteRequest('sh8013');

        $request->addEppNamespace('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/contact_delete_sidn.xml', $xmlString);
    }
}
