<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
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

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_renew.xml', $xmlString);
    }

    public function test_domain_renew_sidn_request(): void
    {
        $request = new DomainRenewRequest(
            'example.com',
            new Carbon('2000-04-03'),
            5
        );

        $request->addEppExtension('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/domain_renew_sidn.xml', $xmlString);
    }
}
