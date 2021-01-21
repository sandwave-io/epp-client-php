<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Services\Keysystems\Domain;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Connection;
use SandwaveIo\EppClient\Services\KeysystemsService;
use SandwaveIo\EppClient\Tests\Services\Util\MockConnectionDriver;

class KeysystemsServiceTransferTest extends TestCase
{
    public function test_info(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login_keysystems.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/domain_transfer_keysystems.xml', __DIR__ . '/../../../data/responses/domain_transfer.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout_keysystems.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new KeysystemsService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->domainTransferStatus('example.com');
    }
}
