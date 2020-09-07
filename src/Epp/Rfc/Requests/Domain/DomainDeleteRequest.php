<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests\Domain;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Commands\Delete;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainDelete;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainName;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;

class DomainDeleteRequest extends Request
{
    /** @var string */
    private $domain;

    public function __construct(string $domain)
    {
        parent::__construct();

        $this->domain = $domain;
    }

    protected function renderElements(): DOMElement
    {
        return Epp::render([
            Command::render([

                Delete::render([
                    DomainDelete::render([
                        DomainName::render([], $this->domain),
                    ]),
                ]),

                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ], null, $this->extensions);
    }
}
