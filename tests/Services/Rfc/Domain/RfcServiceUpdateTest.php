<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Services\Rfc\Domain;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Connection;
use SandwaveIo\EppClient\Services\RfcService;
use SandwaveIo\EppClient\Tests\Services\Util\MockConnectionDriver;

class RfcServiceUpdateTest extends TestCase
{
    public function test_add_nameserver(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/domain_update_add_nameserver.xml', __DIR__ . '/../../../data/responses/domain_update.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new RfcService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->addDomainNameservers('example.com', ['ns2.example.com']);
    }

    public function test_remove_nameserver(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/domain_update_remove_nameserver.xml', __DIR__ . '/../../../data/responses/domain_update.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new RfcService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->removeDomainNameservers('example.com', ['ns1.example.com']);
    }

    public function test_add_contact(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/domain_update_add_contact.xml', __DIR__ . '/../../../data/responses/domain_update.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new RfcService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->addDomainContact('example.com', 'tech', 'mak21');
    }

    public function test_remove_contact(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/domain_update_remove_contact.xml', __DIR__ . '/../../../data/responses/domain_update.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new RfcService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->removeDomainContact('example.com', 'tech', 'sh8013');
    }

    public function test_add_status(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/domain_update_add_status.xml', __DIR__ . '/../../../data/responses/domain_update.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new RfcService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->addDomainStatus('example.com', 'clientHold', 'Payment overdue.');
    }

    public function test_remove_status(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/domain_update_remove_status.xml', __DIR__ . '/../../../data/responses/domain_update.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new RfcService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->removeDomainStatus('example.com', 'clientUpdateProhibited');
    }

    public function test_change_registrant(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/domain_update_change_registrant.xml', __DIR__ . '/../../../data/responses/domain_update.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new RfcService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->updateDomainRegistrant('example.com', 'sh8013');
    }

    public function test_change_password(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/domain_update_change_password.xml', __DIR__ . '/../../../data/responses/domain_update.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new RfcService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->updateDomainPassword('example.com', '2BARfoo');
    }
}
