<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Services\Sidn\Contact;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Connection;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Contact\ContactUpdateRequest;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Objects\ContactPostalInfo;
use SandwaveIo\EppClient\Services\SidnService;
use SandwaveIo\EppClient\Tests\Services\Util\MockConnectionDriver;

class SidnServiceUpdateTest extends TestCase
{
    public function test_fields(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login_sidn.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/contact_update_fields_sidn.xml', __DIR__ . '/../../../data/responses/contact_update.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout_sidn.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new SidnService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->updateContact(
            'sh8013',
            '2fooBAR',
            new ContactPostalInfo(
                null,
                'Dulles',
                'US',
                '',
                '124 Example Dr.',
                'Suite 200',
                null,
                'VA',
                '20166-6503'
            ),
            null,
            '+1.7034444444',
            '',
            [
                ContactUpdateRequest::DISCLOSE_VOICE,
                ContactUpdateRequest::DISCLOSE_EMAIL,
            ],
            true
        );
    }

    public function test_add_status(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login_sidn.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/contact_update_add_status_sidn.xml', __DIR__ . '/../../../data/responses/contact_update.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout_sidn.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new SidnService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->addContactStatus('sh8013', 'clientDeleteProhibited', '');
    }

    public function test_rem_status(): void
    {
        $driver = new MockConnectionDriver($this);

        $driver->expectRequest(__DIR__ . '/../../../data/requests/login_sidn.xml', __DIR__ . '/../../../data/responses/login.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/contact_update_rem_status_sidn.xml', __DIR__ . '/../../../data/responses/contact_update.xml');
        $driver->expectRequest(__DIR__ . '/../../../data/requests/logout_sidn.xml', __DIR__ . '/../../../data/responses/logout.xml');

        $service = new SidnService(new Connection($driver), 'admin', 'secret', 'ABC-12345');

        $service->removeContactStatus('sh8013', 'clientDeleteProhibited', '');
    }
}
