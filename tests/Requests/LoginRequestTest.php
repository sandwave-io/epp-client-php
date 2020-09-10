<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPStan\Testing\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\LoginRequest;

class LoginRequestTest extends TestCase
{
    public function test_login_request(): void
    {
        $request = new LoginRequest('admin', 'secret');

        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/login.xml', $xmlString);
    }

    public function test_login_sidn_request(): void
    {
        $request = new LoginRequest('admin', 'secret');

        $request->addEppNamespace('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        $request->setClientTransactionIdentifier('ABC-12345');

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../data/requests/login_sidn.xml', $xmlString);
    }
}
