<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Element;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;

abstract class Request extends Document
{
    /** @var string|null */
    protected $clientTransactionIdentifier;

    /** @var array<string, string> */
    protected $extensions;

    public function __construct(?string $clientTransactionIdentifier = null, array $extensions = [])
    {
        parent::__construct();

        $this->clientTransactionIdentifier = $clientTransactionIdentifier;
        $this->extensions = $extensions;
    }

    public function getClientTransactionIdentifier(): ?string
    {
        return $this->clientTransactionIdentifier;
    }

    public function setClientTransactionIdentifier(string $clientTransactionIdentifier): void
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

    abstract protected function renderElements(): DOMElement;

    protected function renderEppElement(array $children): DOMElement
    {
        $epp = Epp::render($children);

        foreach ($this->extensions as $name => $extension) {
            $epp->setAttribute($name, $extension);
        }

        return $epp;
    }
}
