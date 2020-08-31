<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc;

use DOMDocument;
use SandwaveIo\EppClient\Exceptions\EppXmlException;

class Document extends DOMDocument
{
    const XML_VERSION = '1.0';
    const XML_ENCODING = 'UTF-8';

    public function __construct()
    {
        parent::__construct(Document::XML_VERSION, Document::XML_ENCODING);
    }

    public function toString(): string
    {
        if (! $xml = $this->saveXML(null, LIBXML_NOEMPTYTAG)) {
            throw new EppXmlException('Error while rendering XML');
        }
        return $xml;
    }

    public static function fromString(string $xml): Document
    {
        $document = new Document();
        if (! $document->loadXML($xml)) {
            throw new EppXmlException('Error while parsing XML');
        }
        return $document;
    }
}
