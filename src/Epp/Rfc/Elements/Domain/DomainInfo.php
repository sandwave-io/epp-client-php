<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Elements\Domain;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Element;

final class DomainInfo extends Element
{
    public static $element = 'domain:info';

    public static function render(array $children = [], ?string $value = null, array $attributes = []): DOMElement
    {
        $element = parent::render($children, $value, $attributes);
        $element->setAttribute('xmlns:domain', 'urn:ietf:params:xml:ns:domain-1.0');
        return $element;
    }
}
