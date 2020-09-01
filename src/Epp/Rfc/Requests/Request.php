<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Element;

abstract class Request extends Document
{
    /** @var string|null */
    protected $clientTransactionIdentifier;

    public function __construct(?string $clientTransactionIdentifier = null)
    {
        parent::__construct();

        $this->clientTransactionIdentifier = $clientTransactionIdentifier;
    }

    public function getClientTransactionIdentifier(): ?string
    {
        return $this->clientTransactionIdentifier;
    }

    public function setClientTransactionIdentifier(string $clientTransactionIdentifier): void
    {
        return $this->clientTransactionIdentifier = $clientTransactionIdentifier;
    }

    public function renderAndAppendChildren(): Request
    {
        Element::setDocument($this);
        $this->appendChild($this->renderElements());
        Element::resetDocument();
        return $this;
    }

    abstract protected function renderElements(): DOMElement;
}
