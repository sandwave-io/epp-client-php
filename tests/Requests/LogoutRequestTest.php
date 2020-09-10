<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPStan\Testing\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\LogoutRequest;

class LogoutRequestTest extends TestCase
{
    public function test_logout_request(): void
    {
        $request = new LogoutRequest();
        $request->addEppNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $request->addEppNamespace('contact', 'urn:ietf:params:xml:ns:contact-1.0');

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/logout.xml', $xmlString);
    }

    public function test_logout_sidn_request(): void
    {
        $request = new LogoutRequest();
        $request->addEppNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $request->addEppNamespace('contact', 'urn:ietf:params:xml:ns:contact-1.0');

        $request->addEppNamespace('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/logout_sidn.xml', $xmlString);
    }
}
