<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Services\Rfc;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Connection;
use SandwaveIo\EppClient\Exceptions\ConnectException;
use SandwaveIo\EppClient\Services\RfcService;
use SandwaveIo\EppClient\Tests\Services\Util\MockConnectionDriver;

class RfcServiceAuthTest extends TestCase
{
    public function test_login(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../data/requests/login.xml', __DIR__ . '/../../data/responses/login.xml');

        $service = new RfcService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->login('admin', 'secret');
    }

    public function test_login_fail(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../data/requests/login.xml', __DIR__ . '/../../data/responses/login_fail.xml');

        $service = new RfcService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $this->expectException(ConnectException::class);
        $this->expectExceptionMessage('Cannot authenticate with EPP backend: [2200] Access denied.');
        $service->login('admin', 'secret');
    }

    public function test_logout(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../data/requests/logout.xml', __DIR__ . '/../../data/responses/logout.xml');

        $service = new RfcService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->logout();
    }
}
