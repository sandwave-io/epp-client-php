<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Services\Sidn\Domain;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Connection;
use SandwaveIo\EppClient\Services\SidnService;
use SandwaveIo\EppClient\Tests\Services\Util\MockConnectionDriver;

class SidnServiceRenewTest extends TestCase
{
    public function test_info(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login_sidn.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/domain_renew_sidn.xml', __DIR__ . '/../../../data/responses/domain_renew.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout_sidn.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new SidnService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->renewDomain('example.com', new Carbon('2000-04-03'), 60);
    }
}
