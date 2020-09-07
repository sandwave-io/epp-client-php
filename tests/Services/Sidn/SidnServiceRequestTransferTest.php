<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Services\Sidn;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Connection;
use SandwaveIo\EppClient\Services\SidnService;
use SandwaveIo\EppClient\Tests\Services\Util\MockConnectionDriver;

class SidnServiceRequestTransferTest extends TestCase
{
    public function test_info(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../data/requests/login_sidn.xml', __DIR__ . '/../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../data/requests/domain_request_transfer_sidn.xml', __DIR__ . '/../../data/responses/domain_request_transfer.xml');
        $driver->expectRequest(__DIR__ . '/../../data/requests/logout_sidn.xml', __DIR__ . '/../../data/responses/logout.xml');

        $service = new SidnService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->transferDomain('example.com', '2fooBAR', 'JD1234-REP', 1);
    }
}
