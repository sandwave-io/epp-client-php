<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Extensions\Requests\Sidn\SidnDomainRenewRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainRenewRequest;

class DomainRenewRequestTest extends TestCase
{
    public function test_domain_renew_request(): void
    {
        $request = new DomainRenewRequest(
            'example.com',
            new Carbon('2000-04-03'),
            5
        );
        $request->addEppNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $request->addEppNamespace('contact', 'urn:ietf:params:xml:ns:contact-1.0');

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_renew.xml', $xmlString);
    }

    public function test_domain_renew_sidn_request(): void
    {
        $request = new SidnDomainRenewRequest(
            'example.com',
            new Carbon('2000-04-03'),
            60
        );
        $request->addEppNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $request->addEppNamespace('contact', 'urn:ietf:params:xml:ns:contact-1.0');

        $request->addEppNamespace('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_renew_sidn.xml', $xmlString);
    }
}
