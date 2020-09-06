<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Elements\DomainTransfer;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Element;

final class Transfer extends Element
{
    public static $element = 'transfer';

    public static function render(array $children = [], ?string $value = null, array $attributes = []): DOMElement
    {
        $element = parent::render($children, $value, $attributes);
        $element->setAttribute('op', 'query');
        return $element;
    }
}
