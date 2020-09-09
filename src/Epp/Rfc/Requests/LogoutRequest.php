<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Commands\Logout;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;

class LogoutRequest extends Request
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function renderElements(): DOMElement
    {
        return Epp::render([
            Command::render([
                Logout::render(),
                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
            $this->renderExtension(),
        ], null, $this->extensions);
    }
}
