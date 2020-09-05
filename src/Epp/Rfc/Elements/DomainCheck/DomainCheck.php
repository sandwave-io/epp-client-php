<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Elements\DomainCheck;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Element;

final class DomainCheck extends Element
{
    public static $element = 'domain:check';

    public static function render(array $children = [], ?string $value = null): DOMElement
    {
        $element = parent::render($children, $value);
        $element->setAttribute('xmlns:domain', 'urn:ietf:params:xml:ns:domain-1.0');
        return $element;
    }
}
