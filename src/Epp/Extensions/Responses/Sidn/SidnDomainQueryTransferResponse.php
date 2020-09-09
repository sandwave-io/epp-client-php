<?php


namespace SandwaveIo\EppClient\Epp\Extensions\Responses\Sidn;

use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainQueryTransferResponse;

class SidnDomainQueryTransferResponse extends DomainQueryTransferResponse
{
    public function getPolledCommand(): string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:command');
        return $result->item(0)->nodeValue;
    }

    public function getPolledResultCode(): ?string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:result/@code');
        foreach ($result as $code) {
            return $code->nodeValue;
        }
        return null;
    }

    public function getPolledResultMessage(): string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:result/epp:msg');
        return $result->item(0)->nodeValue;
    }

    public function getPolledDomainname(): string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:name');
        return $result->item(0)->nodeValue;
    }

    public function getPolledTransferStatus(): string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:trStatus');
        return $result->item(0)->nodeValue;
    }

    public function getPolledRequestClientId(): string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:reID');
        return $result->item(0)->nodeValue;
    }

    public function getPolledRequestDate(): string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:reDate');
        return $result->item(0)->nodeValue;
    }

    public function getPolledActionClientId(): string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:acID');
        return $result->item(0)->nodeValue;
    }

    public function getPolledActionDate(): string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:acDate');
        return $result->item(0)->nodeValue;
    }

    public function getPolledTransactionId(): string
    {
        $result = $this->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:trID/epp:svTRID');
        return $result->item(0)->nodeValue;
    }
}