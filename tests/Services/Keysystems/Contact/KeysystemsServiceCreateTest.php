<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Services\Keysystems\Contact;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Connection;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Contact\ContactCreateRequest;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Objects\ContactPostalInfo;
use SandwaveIo\EppClient\Services\KeysystemsService;
use SandwaveIo\EppClient\Tests\Services\Util\MockConnectionDriver;

class KeysystemsServiceCreateTest extends TestCase
{
    public function test_create_business(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login_keysystems.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/contact_create_business_keysystems.xml', __DIR__ . '/../../../data/responses/contact_create.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout_keysystems.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new KeysystemsService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->createContact(
            'sh8013',
            'jdoe@example.com',
            '2fooBAR',
            new ContactPostalInfo(
                'John Doe',
                'Dulles',
                'US',
                'Example Inc.',
                '123 Example Dr.',
                'Suite 100',
                null,
                'VA',
                '20166-6503'
            ),
            null,
            '+1.7035555555',
            '+1.7035555556',
            [
                ContactCreateRequest::DISCLOSE_VOICE,
                ContactCreateRequest::DISCLOSE_EMAIL,
            ],
            false
        );
    }

    public function test_create_personal(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login_keysystems.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/contact_create_keysystems.xml', __DIR__ . '/../../../data/responses/contact_create.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout_keysystems.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new KeysystemsService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->createContact(
            'sh8013',
            'jdoe@example.com',
            '2fooBAR',
            new ContactPostalInfo(
                'John Doe',
                'Dulles',
                'US',
                null,
                '123 Example Dr.',
                'Suite 100',
                null,
                'VA',
                '20166-6503'
            ),
            null,
            '+1.7035555555',
            '+1.7035555556',
            [
                ContactCreateRequest::DISCLOSE_VOICE,
                ContactCreateRequest::DISCLOSE_EMAIL,
            ],
            false
        );
    }
}
