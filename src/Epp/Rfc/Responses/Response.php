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
        $result = $this->get('epp.response.result');
        assert($result instanceof DOMElement);
        return ResultCode::fromString($result->getAttribute('code'));
    }

    public function getResultMessage(): string
    {
        $result = $this->get('epp.response.result');
        assert($result instanceof DOMElement);
        return trim($result->textContent);
    }

    public function getClientTransactionIdentifier(): string
    {
        $result = $this->get('epp.response.trID.clTRID');
        assert($result instanceof DOMElement);
        return trim($result->textContent);
    }

    public function getServerTransactionIdentifier(): string
    {
        $result = $this->get('epp.response.trID.svTRID');
        assert($result instanceof DOMElement);
        return trim($result->textContent);
    }

    /** @deprecated User get instead. */
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
        $xpath  = new DOMXPath($this->document);
        $result = $xpath->query($expression);

        if ($result === false) {
            throw new EppXmlException("DOMXPath query [{$expression}] had no results.");
        }

        return $result;
    }

    /**
     * Query the document like "epp.result.x".
     */
    protected function get(string $query, ?DOMNodeList $context = null): ?DOMElement
    {
        if (! $context) {
            $context = $this->document->childNodes;
        }

        $query = explode('.', $query);

        foreach ($context as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }
            if ($node->localName === $query[0]) {
                if (count($query) === 1) {
                    return $node;
                }
                array_shift($query);
                return $this->get(implode('.', $query), $node->childNodes);
            }
        }

        return null;
    }
}
