<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Elements;

use DOMDocument;
use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use Webmozart\Assert\Assert;

abstract class Element
{
    /** @var string */
    protected static $element = 'element';

    /** @var DOMDocument|null */
    private static $document;

    public static function setDocument(DOMDocument $document): void
    {
        Element::$document = $document;
    }

    public static function resetDocument(): void
    {
        Element::$document = new DOMDocument(Document::XML_VERSION, Document::XML_ENCODING);
    }

    public static function getDocument(): DOMDocument
    {
        if (Element::$document === null) {
            Element::$document = new DOMDocument(Document::XML_VERSION, Document::XML_ENCODING);
        }

        return Element::$document;
    }

    /**
     * Render the current element, then append all given children to it, and return it.
     *
     * @param array<DOMElement|null> $children
     * @param string|null $value
     *
     * @return DOMElement
     */
    public static function render(array $children = [], ?string $value = null): DOMElement
    {
        $element = Element::getDocument()->createElement(static::$element, (string) $value);

        return Element::appendChildren($element, $children);
    }

    /**
     * @param DOMElement             $element
     * @param array<DOMElement|null> $children
     *
     * @return DOMElement
     */
    protected static function appendChildren(DOMElement $element, array $children): DOMElement
    {
        foreach ($children as $child) {
            if ($child instanceof DOMElement) {
                $element->appendChild($child);
            }
        }
        return $element;
    }
}
