<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests\Domain;

use Carbon\Carbon;
use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Commands\Renew;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainCurrentExpirationDate;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainName;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainPeriod;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainRenew;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;

class DomainRenewRequest extends Request
{
    /** @var string */
    private $domain;

    /** @var Carbon */
    private $currentExpirationDate;

    /** @var int|null */
    private $renewalPeriod;

    public function __construct(string $domain, Carbon $currentExpirationDate, ?int $renewalPeriod = null)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->currentExpirationDate = $currentExpirationDate;
        $this->renewalPeriod = $renewalPeriod;
    }

    protected function renderElements(): DOMElement
    {
        return Epp::render([
            Command::render([
                Renew::render([
                    DomainRenew::render([
                        DomainName::render([], $this->domain),
                        DomainCurrentExpirationDate::render([], $this->currentExpirationDate->format('Y-m-d')),
                        $this->renewalPeriod ? DomainPeriod::render([], (string) $this->renewalPeriod, ['unit' => 'y']) : null,
                    ]),
                ]),

                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ], null, $this->extensions);
    }
}
