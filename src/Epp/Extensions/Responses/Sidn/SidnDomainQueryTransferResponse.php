<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Extensions\Responses\Sidn;

use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainQueryTransferResponse;

class SidnDomainQueryTransferResponse extends DomainQueryTransferResponse
{
    public function getPolledCommand(): ?string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:command');
        if (! $item = $result->item(0)) {
            return null;
        }

        return $item->nodeValue;
    }

    public function getPolledResultCode(): ?string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:result/@code');
        foreach ($result as $code) {
            return $code->nodeValue;
        }
        return null;
    }

    public function getPolledResultMessage(): ?string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:result/epp:msg');
        if (! $item = $result->item(0)) {
            return null;
        }

        return $item->nodeValue;
    }

    public function getPolledDomainname(): ?string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:name');
        if (! $item = $result->item(0)) {
            return null;
        }

        return $item->nodeValue;
    }

    public function getPolledTransferStatus(): ?string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:trStatus');
        if (! $item = $result->item(0)) {
            return null;
        }

        return $item->nodeValue;
    }

    public function getPolledRequestClientId(): ?string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:reID');
        if (! $item = $result->item(0)) {
            return null;
        }

        return $item->nodeValue;
    }

    public function getPolledRequestDate(): ?string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:reDate');
        if (! $item = $result->item(0)) {
            return null;
        }

        return $item->nodeValue;
    }

    public function getPolledActionClientId(): ?string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:acID');
        if (! $item = $result->item(0)) {
            return null;
        }

        return $item->nodeValue;
    }

    public function getPolledActionDate(): ?string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:acDate');
        if (! $item = $result->item(0)) {
            return null;
        }

        return $item->nodeValue;
    }

    public function getPolledTransactionId(): ?string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:trID/epp:svTRID');
        if (! $item = $result->item(0)) {
            return null;
        }

        return $item->nodeValue;
    }
}
