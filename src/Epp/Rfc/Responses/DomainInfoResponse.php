<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Responses;

use Carbon\Carbon;
use DOMElement;

class DomainInfoResponse extends Response
{
    public function getDomainName(): string
    {
        return trim($this->getElement('name')->textContent);
    }

    public function getRepositoryObjectIdentifier(): string
    {
        return trim($this->getElement('roid')->textContent);
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

    /** @return array<string> */
    public function getRegistrants(): array
    {
        $list = $this->document->getElementsByTagName('registrant');
        $registrants = [];

        foreach ($list as $item) {
            if ($item instanceof DOMElement) {
                $registrants[] = trim($item->textContent);
            }
        }

        return $registrants;
    }

    /** @return array<string,string> */
    public function getContacts(): array
    {
        $list = $this->document->getElementsByTagName('contact');
        $contacts = [];

        foreach ($list as $item) {
            if ($item instanceof DOMElement) {
                if ($type = $item->getAttribute('type')) {
                    $contacts[$type] = trim($item->textContent);
                }
            }
        }

        return $contacts;
    }

    /** @return array<string> */
    public function getNameServers(): array
    {
        if ($this->document->getElementsByTagName('ns')->count() === 0) {
            return [];
        }
        $list = $this->document
            ->getElementsByTagName('ns')
            ->item(0)
            ->childNodes;
        $nameservers = [];

        foreach ($list as $item) {
            if ($item instanceof DOMElement && $item->localName === 'hostObj') {
                $nameservers[] = trim($item->textContent);
            }
        }

        return $nameservers;
    }

    /** @return array<string> */
    public function getHosts(): array
    {
        $list = $this->document->getElementsByTagName('host');
        $hosts = [];

        foreach ($list as $item) {
            if ($item instanceof DOMElement) {
                $hosts[] = trim($item->textContent);
            }
        }

        return $hosts;
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

    public function getExpirationDate(): ?Carbon
    {
        if ($this->document->getElementsByTagName('exDate')->count() > 0) {
            return new Carbon(trim($this->getElement('exDate')->textContent));
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

    public function getPassword(): ?string
    {
        if ($this->document->getElementsByTagName('authInfo')->count() === 0) {
            return null;
        }
        $list = $this->document
            ->getElementsByTagName('authInfo')
            ->item(0)
            ->childNodes;

        foreach ($list as $item) {
            if ($item instanceof DOMElement && $item->localName === 'pw') {
                return trim($item->textContent);
            }
        }

        return null;
    }
}
