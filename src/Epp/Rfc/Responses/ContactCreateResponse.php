<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Responses;

use Carbon\Carbon;

class ContactCreateResponse extends Response
{
    public function getContactId(): string
    {
        return trim($this->getElement('id')->textContent);
    }

    public function getCreatedDate(): Carbon
    {
        return new Carbon(trim($this->getElement('crDate')->textContent));
    }
}
