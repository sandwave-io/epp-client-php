<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Elements\Contact;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Element;

final class ContactCreate extends Element
{
    public static $element = 'contact:create';

    public static function render(array $children = [], ?string $value = null, array $attributes = []): DOMElement
    {
        $element = parent::render($children, $value, $attributes);
        $element->setAttribute('xmlns:contact', 'urn:ietf:params:xml:ns:contact-1.0');
        return $element;
    }
}
