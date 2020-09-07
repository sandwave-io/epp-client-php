<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Responses;

use Carbon\Carbon;

class DomainCreateResponse extends Response
{
    public function getDomainName(): string
    {
        return trim($this->getElement('name')->textContent);
    }

    public function getCreatedDate(): Carbon
    {
        return new Carbon(trim($this->getElement('crDate')->textContent));
    }

    public function getExpiryDate(): ?Carbon
    {
        if ($this->document->getElementsByTagName('exDate')->count() > 0) {
            return new Carbon(trim($this->getElement('exDate')->textContent));
        }

        return null;
    }
}
