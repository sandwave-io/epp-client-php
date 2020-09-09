<?php


namespace SandwaveIo\EppClient\Epp\Extensions\Responses\Sidn;

use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainInfoResponse;

class SidnDomainInfoResponse extends DomainInfoResponse
{
    public function getDomainPeriod(): ?string
    {
        $result = $this->query('/epp:epp/epp:response/epp:extension/sidn-ext-epp:ext/sidn-ext-epp:infData/sidn-ext-epp:domain/sidn-ext-epp:period');
        if (! $item = $result->item(0)) {
            return null;
        }
        return $item->nodeValue;
    }

    public function getDomainOptout(): ?string
    {
        $result = $this->query('/epp:epp/epp:response/epp:extension/sidn-ext-epp:ext/sidn-ext-epp:infData/sidn-ext-epp:domain/sidn-ext-epp:optOut');
        if (! $item = $result->item(0)) {
            return null;
        }
        return $item->nodeValue;
    }

    public function getDomainLimited(): ?string
    {
        $result = $this->query('/epp:epp/epp:response/epp:extension/sidn-ext-epp:ext/sidn-ext-epp:infData/sidn-ext-epp:domain/sidn-ext-epp:limited');
        if (! $item = $result->item(0)) {
            return null;
        }
        return $item->nodeValue;
    }
}