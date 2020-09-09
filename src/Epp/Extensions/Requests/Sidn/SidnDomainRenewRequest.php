<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Extensions\Requests\Sidn;

use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainRenewRequest;

class SidnDomainRenewRequest extends DomainRenewRequest
{
    protected $renewalUnit = DomainRenewRequest::RENEWAL_UNIT_MONTHS;
}
