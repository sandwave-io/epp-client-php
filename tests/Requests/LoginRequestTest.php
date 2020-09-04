<?php


namespace SandwaveIo\EppClient\Tests\Requests;


use PHPStan\Testing\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\LoginRequest;

class LoginRequestTest extends TestCase
{
    public function test_login_request(): void
    {
        $request = new LoginRequest(
            'admin',
            'secret',
            null,
            [],
            [],
            'ABCD-1234'
        );

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__.'/data/login.xml', $xmlString);
    }

    public function test_login_sidn_request(): void
    {
        $request = new LoginRequest(
            'admin',
            'secret',
            null,
            ['sidn-ext-epp' => 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0'],
            [],
            'ABCD-1234'
        );

        $xmlString = $request->renderAndAppendChildren()->toString();
        $this->assertXmlStringEqualsXmlFile(__DIR__.'/data/login_sidn.xml', $xmlString);
    }
}