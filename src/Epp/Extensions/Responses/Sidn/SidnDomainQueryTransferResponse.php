<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Extensions\Responses\Sidn;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainQueryTransferResponse;

class SidnDomainQueryTransferResponse extends DomainQueryTransferResponse
{
    public function getPolledCommand(): ?string
    {
        $result = $this->get('epp.response.resData.pollData.command');
        assert($result instanceof DOMElement);
        return trim($result->textContent);
    }

    public function getPolledResultCode(): ?string
    {
        $resultElement = $this->get('epp.response.resData.pollData.data.result');
        assert($resultElement instanceof DOMElement);
        return $resultElement->getAttribute('code');
    }

    public function getPolledResultMessage(): ?string
    {
        $result = $this->get('epp.response.resData.pollData.data.result.msg');
        assert($result instanceof DOMElement);
        return trim($result->textContent);
    }

    public function getPolledDomainname(): ?string
    {
        $result = $this->get('epp.response.resData.pollData.data.resData.trnData.name');
        assert($result instanceof DOMElement);
        return trim($result->textContent);
    }

    public function getPolledTransferStatus(): ?string
    {
        $result = $this->get('epp.response.resData.pollData.data.resData.trnData.trStatus');
        assert($result instanceof DOMElement);
        return trim($result->textContent);
    }

    public function getPolledRequestClientId(): ?string
    {
        $result = $this->get('epp.response.resData.pollData.data.resData.trnData.reID');
        assert($result instanceof DOMElement);
        return trim($result->textContent);
    }

    public function getPolledRequestDate(): ?string
    {
        $result = $this->get('epp.response.resData.pollData.data.resData.trnData.reDate');
        assert($result instanceof DOMElement);
        return trim($result->textContent);
    }

    public function getPolledActionClientId(): ?string
    {
        $result = $this->get('epp.response.resData.pollData.data.resData.trnData.acID');
        assert($result instanceof DOMElement);
        return trim($result->textContent);
    }

    public function getPolledActionDate(): ?string
    {
        $result = $this->get('epp.response.resData.pollData.data.resData.trnData.acDate');
        assert($result instanceof DOMElement);
        return trim($result->textContent);
    }

    public function getPolledTransactionId(): ?string
    {
        $result = $this->get('epp.response.resData.pollData.data.trID.svTRID');
        assert($result instanceof DOMElement);
        return trim($result->textContent);
    }
}
