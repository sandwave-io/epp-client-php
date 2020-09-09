<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Element;

abstract class Request extends Document
{
    /** @var string|null */
    protected $clientTransactionIdentifier;

    /** @var array<string, string> */
    protected $extensions = [];

    public function addEppExtension(string $name, string $url): void
    {
        $this->extensions[$name] = $url;
    }

    public function getClientTransactionIdentifier(): ?string
    {
        return $this->clientTransactionIdentifier;
    }

    public function setClientTransactionIdentifier(?string $clientTransactionIdentifier): void
    {
        $this->clientTransactionIdentifier = $clientTransactionIdentifier;
    }

    public function renderAndAppendChildren(): Request
    {
        Element::setDocument($this);
        $this->appendChild($this->renderElements());
        Element::resetDocument();
        return $this;
    }

    protected function renderExtension(): ?DOMelement
    {
        // This may be overwritten to render extensions.
        return null;
    }

    abstract protected function renderElements(): DOMElement;
}
