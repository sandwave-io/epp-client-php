<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;

class DomainUpdateRequest extends Request
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

                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ], null, $this->extensions);
    }
}
