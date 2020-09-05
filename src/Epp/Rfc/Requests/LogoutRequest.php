<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Logout\Logout;

class LogoutRequest extends Request
{
    public function __construct(array $extensions = [], ?string $clientTransactionIdentifier = null)
    {
        parent::__construct($clientTransactionIdentifier, $extensions);
    }

    protected function renderElements(): DOMElement
    {
        return Epp::render([
            Command::render([
                Logout::render(),
                ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier),
            ]),
        ], null, $this->extensions);
    }
}
