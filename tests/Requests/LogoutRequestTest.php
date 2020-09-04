<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Requests;

use PHPStan\Testing\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\LogoutRequest;

class LogoutRequestTest extends TestCase
{
    public function test_logout_request(): void
    {
        $request = new LogoutRequest(
            [],
            'ABCD-1234'
        );

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/data/logout.xml', $xmlString);
    }

    public function test_logout_sidn_request(): void
    {
        $request = new LogoutRequest(
            ['sidn-ext-epp' => 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0'],
            'ABCD-1234'
        );

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/data/logout_sidn.xml', $xmlString);
    }
}
