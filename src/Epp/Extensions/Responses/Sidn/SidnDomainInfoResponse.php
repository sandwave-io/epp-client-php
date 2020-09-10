<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Extensions\Responses\Sidn;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainInfoResponse;

class SidnDomainInfoResponse extends DomainInfoResponse
{
    public function getDomainPeriod(): ?string
    {
        $result = $this->get('epp.response.extension.ext.infData.domain.period');
        assert($result instanceof DOMElement);
        return trim($result->textContent);
    }

    public function getDomainOptout(): ?string
    {
        $result = $this->get('epp.response.extension.ext.infData.domain.optOut');
        assert($result instanceof DOMElement);
        return trim($result->textContent);
    }

    public function getDomainLimited(): ?string
    {
        $result = $this->get('epp.response.extension.ext.infData.domain.limited');
        assert($result instanceof DOMElement);
        return trim($result->textContent);
    }
}
