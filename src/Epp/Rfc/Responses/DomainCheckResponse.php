<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Responses;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Objects\DomainCheckData;
use SandwaveIo\EppClient\Exceptions\EppXmlException;

class DomainCheckResponse extends Response
{
    /** @return array<DomainCheckData> */
    public function getCheckData(): array
    {
        $checkData = $this->getElement('chkData');

        if (! $checkData instanceof DOMElement) {
            throw new EppXmlException('<domain:chkData/> cannot be found in the response.');
        }

        $data = [];
        foreach ($checkData->childNodes as $node) {
            if ($node instanceof DOMElement) {
                $data[] = DomainCheckData::fromXML($node);
            }
        }

        return $data;
    }
}
