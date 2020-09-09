<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Services\Rfc\Contact;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Connection;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Contact\ContactCreateRequest;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Objects\ContactPostalInfo;
use SandwaveIo\EppClient\Services\RfcService;
use SandwaveIo\EppClient\Tests\Services\Util\MockConnectionDriver;

class RfcServiceCreateTest extends TestCase
{
    public function test_info(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/contact_create.xml', __DIR__ . '/../../../data/responses/contact_create.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new RfcService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

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
}
