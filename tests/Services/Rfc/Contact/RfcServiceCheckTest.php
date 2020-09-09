<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Services\Rfc\Contact;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Connection;
use SandwaveIo\EppClient\Services\RfcService;
use SandwaveIo\EppClient\Tests\Services\Util\MockConnectionDriver;

class RfcServiceCheckTest extends TestCase
{
    public function test_check(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/contact_check.xml', __DIR__ . '/../../../data/responses/contact_check.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new RfcService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $response = $service->checkContacts(['sh8013', 'sah8013', '8013sah']);

        $this->assertSame('1000', (string) $response->getResultCode());
    }
}
