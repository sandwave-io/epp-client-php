<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Responses;

use Carbon\Carbon;
use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Objects\ContactPostalInfo;

class ContactInfoResponse extends Response
{
    public function getContactId(): string
    {
        return trim($this->getElement('id')->textContent);
    }

    public function getRepositoryObjectIdentifier(): string
    {
        return trim($this->getElement('roid')->textContent);
    }

    public function getEmail(): string
    {
        return trim($this->getElement('email')->textContent);
    }

    /** @return array<string> */
    public function getStatuses(): array
    {
        $list = $this->document->getElementsByTagName('status');
        $statuses = [];

        foreach ($list as $item) {
            if ($item instanceof DOMElement) {
                if ($status = $item->getAttribute('s')) {
                    $statuses[] = $status;
                }
            }
        }

        return $statuses;
    }

    /** @return array<ContactPostalInfo> */
    public function getPostalInformation(): array
    {
        $list = $this->document->getElementsByTagName('postalInfo');
        $postalInfoEntities = [];

        foreach ($list as $item) {
            if ($item instanceof DOMElement) {
                $postalInfoEntities[] = ContactPostalInfo::fromXML($item);
            }
        }

        return $postalInfoEntities;
    }

    public function getVoice(): ?string
    {
        if ($this->document->getElementsByTagName('voice')->count() > 0) {
            return trim($this->getElement('voice')->textContent);
        }

        return null;
    }

    public function getFax(): ?string
    {
        if ($this->document->getElementsByTagName('fax')->count() > 0) {
            return trim($this->getElement('fax')->textContent);
        }

        return null;
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

    public function getClientId(): string
    {
        return trim($this->getElement('clID')->textContent);
    }

    public function getCreationClientId(): ?string
    {
        if ($this->document->getElementsByTagName('crID')->count() > 0) {
            return trim($this->getElement('crID')->textContent);
        }

        return null;
    }

    public function getLastUpdateClientId(): ?string
    {
        if ($this->document->getElementsByTagName('upID')->count() > 0) {
            return trim($this->getElement('upID')->textContent);
        }

        return null;
    }

    public function getCreatedDate(): ?Carbon
    {
        if ($this->document->getElementsByTagName('crDate')->count() > 0) {
            return new Carbon(trim($this->getElement('crDate')->textContent));
        }

        return null;
    }

    public function getUpdatedDate(): ?Carbon
    {
        if ($this->document->getElementsByTagName('upDate')->count() > 0) {
            return new Carbon(trim($this->getElement('upDate')->textContent));
        }

        return null;
    }

    public function getTransferedDate(): ?Carbon
    {
        if ($this->document->getElementsByTagName('trDate')->count() > 0) {
            return new Carbon(trim($this->getElement('trDate')->textContent));
        }

        return null;
    }
}
