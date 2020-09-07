<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Responses;

use Carbon\Carbon;

class DomainRenewResponse extends Response
{
    public function getDomainName(): string
    {
        return trim($this->getElement('name')->textContent);
    }

    public function getExpiryDate(): ?Carbon
    {
        if ($this->document->getElementsByTagName('exDate')->count() > 0) {
            return new Carbon(trim($this->getElement('exDate')->textContent));
        }

        return null;
    }
}
