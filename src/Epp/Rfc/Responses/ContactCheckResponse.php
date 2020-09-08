<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Responses;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Objects\ContactCheckData;
use SandwaveIo\EppClient\Exceptions\EppXmlException;

class ContactCheckResponse extends Response
{
    /** @return array<ContactCheckData> */
    public function getCheckData(): array
    {
        $checkData = $this->getElement('chkData');

        if (! $checkData instanceof DOMElement) {
            throw new EppXmlException('<contact:chkData/> cannot be found in the response.');
        }

        $data = [];
        foreach ($checkData->childNodes as $node) {
            if ($node instanceof DOMElement) {
                $data[] = ContactCheckData::fromXML($node);
            }
        }

        return $data;
    }
}
