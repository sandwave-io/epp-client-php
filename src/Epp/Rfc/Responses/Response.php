<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Responses;

use DOMElement;
use DOMNodeList;
use DOMXPath;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Objects\ResultCode;
use SandwaveIo\EppClient\Exceptions\EppXmlException;

abstract class Response
{
    /** @var Document */
    protected $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function isSuccess(): bool
    {
        return $this->getResultCode()->isSuccess();
    }

    public function getResultCode(): ResultCode
    {
        return ResultCode::fromString($this->getElement('result')->getAttribute('code'));
    }

    public function getResultMessage(): string
    {
        return trim($this->getElement('result')->textContent);
    }

    public function getClientTransactionIdentifier(): string
    {
        return trim($this->getElement('clTRID')->textContent);
    }

    public function getServerTransactionIdentifier(): string
    {
        return trim($this->getElement('svTRID')->textContent);
    }

    protected function getElement(string $tag): DOMElement
    {
        if (! $result = $this->document->getElementsByTagName($tag)->item(0)) {
            throw new EppXmlException("Cannot resolve <{$tag}/> tag from response.");
        }

        if (! $result instanceof DOMElement) {
            throw new EppXmlException("Resolved tag <{$tag}/> tag is not a DOMElement.");
        }
        return $result;
    }

    protected function query(string $expression): DOMNodeList
    {
        $result = (new DOMXPath($this->document))->query($expression);

        if ($result === false) {
            throw new EppXmlException("DOMXPath query [{$expression}] had no results.");
        }

        return $result;
    }
}
