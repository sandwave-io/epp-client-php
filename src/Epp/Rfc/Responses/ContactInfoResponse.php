<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Responses;

use DOMElement;

class ContactInfoResponse extends Response
{
    public function getContactId(): string
    {
        return trim($this->getElement('id')->textContent);
    }

    public function getPassword(): ?string
    {
        if (! $parent = $this->document->getElementsByTagName('authInfo')->item(0)) {
            return null;
        }

        foreach ($parent->childNodes as $item) {
            if ($item instanceof DOMElement && $item->localName === 'pw') {
                return trim($item->textContent);
            }
        }

        return null;
    }
}
