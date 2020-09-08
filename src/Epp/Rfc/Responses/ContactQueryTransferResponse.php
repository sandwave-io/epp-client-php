<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Responses;

use Carbon\Carbon;

class ContactQueryTransferResponse extends Response
{
    public function getContactId(): string
    {
        return trim($this->getElement('id')->textContent);
    }

    public function getTransferStatus(): string
    {
        return trim($this->getElement('trStatus')->textContent);
    }

    public function getRequestClientId(): string
    {
        return trim($this->getElement('reID')->textContent);
    }

    public function getRequestDate(): Carbon
    {
        return new Carbon(trim($this->getElement('reDate')->textContent));
    }

    public function getAcceptanceClientId(): string
    {
        return trim($this->getElement('acID')->textContent);
    }

    public function getAcceptanceDate(): Carbon
    {
        return new Carbon(trim($this->getElement('acDate')->textContent));
    }
}
