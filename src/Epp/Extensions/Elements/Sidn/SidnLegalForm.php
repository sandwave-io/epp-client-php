<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Extensions\Elements\Sidn;

use SandwaveIo\EppClient\Epp\Rfc\Elements\Element;

final class SidnLegalForm extends Element
{
    const LEGAL_FORM_PRIVATE = 'PERSOON';
    const LEGAL_FORM_BUSINESS = 'ANDERS';

    public static $element = 'sidn-ext-epp:legalForm';
}
