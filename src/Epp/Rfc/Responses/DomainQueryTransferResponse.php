<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Responses;

use Carbon\Carbon;

class DomainQueryTransferResponse extends Response
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




    // TODO: T H O M A S

    public function getPolledCommand() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:command');
        return $result->item(0)->nodeValue;
    }

    public function getPolledResultCode() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:result/@code');
        foreach ($result as $code) {
            return $code->nodeValue;
        }
        return null;
    }

    public function getPolledResultMessage() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:result/epp:msg');
        return $result->item(0)->nodeValue;
    }

    public function getPolledDomainname() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:name');
        return $result->item(0)->nodeValue;
    }

    public function getPolledTransferStatus() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:trStatus');
        return $result->item(0)->nodeValue;
    }

    public function getPolledRequestClientId() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:reID');
        return $result->item(0)->nodeValue;
    }

    public function getPolledRequestDate() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:reDate');
        return $result->item(0)->nodeValue;
    }

    public function getPolledActionClientId() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:acID');
        return $result->item(0)->nodeValue;
    }

    public function getPolledActionDate() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:acDate');
        return $result->item(0)->nodeValue;
    }

    public function getPolledTransactionId() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:trID/epp:svTRID');
        return $result->item(0)->nodeValue;
    }
}
