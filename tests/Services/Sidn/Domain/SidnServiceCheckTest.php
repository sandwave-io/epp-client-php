<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Services\Sidn\Domain;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Connection;
use SandwaveIo\EppClient\Services\SidnService;
use SandwaveIo\EppClient\Tests\Services\Util\MockConnectionDriver;

class SidnServiceCheckTest extends TestCase
{
    public function test_check(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login_sidn.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/domain_check_sidn.xml', __DIR__ . '/../../../data/responses/domain_check.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout_sidn.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new SidnService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $response = $service->checkDomains(['example.com', 'example.net', 'example.org']);

        $this->assertSame('1000', (string) $response->getResultCode());
    }
}
