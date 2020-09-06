<?php


namespace SandwaveIo\EppClient\Epp\Rfc\Responses\Objects;


use DOMElement;
use SandwaveIo\EppClient\Exceptions\EppXmlException;
use SandwaveIo\EppClient\Exceptions\UnexpectedValueException;

class DomainCheckData
{
    /** @var string */
    public $domain;

    /** @var bool */
    public $isAvailable;

    /** @var string|null */
    public $reason;

    private function __construct(string $domain, bool $isAvailable, ?string $reason = null)
    {
        $this->domain = $domain;
        $this->isAvailable = $isAvailable;
        $this->reason = $reason;
    }

    public static function fromXML(DOMElement $domainCheckData): DomainCheckData
    {
        if (! $domainCheckData->localName === 'cd') {
            throw new EppXmlException('DomainCheckData can only be parsed from a <domain:cd/> element.');
        }

        $nameTag   = $domainCheckData->getElementsByTagName('name')->item(0);
        $reasonTag = $domainCheckData->getElementsByTagName('reason')->item(0);

        if (! $nameTag instanceof DOMElement) {
            throw new EppXmlException('DomainCheckData must contain a <domain:name/> element.');
        }

        return new DomainCheckData(
            trim($nameTag->textContent),
            (bool) $nameTag->getAttribute('avail'),
            ($reasonTag instanceof DOMElement) ? trim($reasonTag->textContent) : null
        );
    }
}