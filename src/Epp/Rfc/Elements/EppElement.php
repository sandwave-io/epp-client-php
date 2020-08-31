<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Elements;

use DOMElement;

final class EppElement extends Element
{
    public static $element = 'epp';

    public static function render(array $children = [], ?string $value = null): DOMElement
    {
        $element = parent::render($children, $value);
        $element->setAttribute('xmlns', 'urn:ietf:params:xml:ns:epp-1.0');
        return $element;
    }
}
