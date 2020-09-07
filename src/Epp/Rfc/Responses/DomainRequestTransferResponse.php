<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Responses;

use Carbon\Carbon;

class DomainRequestTransferResponse extends Response
{
    public function getDomainName(): string
    {
        return trim($this->getElement('name')->textContent);
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

    public function getExpiryDate(): ?Carbon
    {
        if ($this->document->getElementsByTagName('exDate')->count() > 0) {
            return new Carbon(trim($this->getElement('exDate')->textContent));
        }

        return null;
    }
}
