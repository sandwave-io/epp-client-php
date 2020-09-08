<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Responses\Objects;

use DOMElement;
use SandwaveIo\EppClient\Exceptions\EppXmlException;

class ContactCheckData
{
    /** @var string */
    public $contact;

    /** @var bool */
    public $isAvailable;

    /** @var string|null */
    public $reason;

    private function __construct(string $contact, bool $isAvailable, ?string $reason = null)
    {
        $this->contact = $contact;
        $this->isAvailable = $isAvailable;
        $this->reason = $reason;
    }

    public static function fromXML(DOMElement $contactCheckData): ContactCheckData
    {
        if ($contactCheckData->localName !== 'cd') {
            throw new EppXmlException('ContactCheckData can only be parsed from a <contact:cd/> element.');
        }

        $nameTag   = $contactCheckData->getElementsByTagName('id')->item(0);
        $reasonTag = $contactCheckData->getElementsByTagName('reason')->item(0);

        if (! $nameTag instanceof DOMElement) {
            throw new EppXmlException('ContactCheckData must contain a <contact:name/> element.');
        }

        return new ContactCheckData(
            trim($nameTag->textContent),
            (bool) $nameTag->getAttribute('avail'),
            ($reasonTag instanceof DOMElement) ? trim($reasonTag->textContent) : null
        );
    }
}
