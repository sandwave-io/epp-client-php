<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Services\Keysystems\Domain;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Connection;
use SandwaveIo\EppClient\Services\KeysystemsService;
use SandwaveIo\EppClient\Tests\Services\Util\MockConnectionDriver;

class KeysystemsServiceCreateTest extends TestCase
{
    public function test_info(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login_keysystems.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/domain_create_keysystems.xml', __DIR__ . '/../../../data/responses/domain_create.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout_keysystems.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new KeysystemsService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->createDomain(
            'example.com',
            2,
            ['ns1.example.net', 'ns2.example.net'],
            'jd1234',
            [
                'admin' => 'sh8013',
                'tech' => 'sh8013',
            ],
            '2fooBAR'
        );
    }
}
