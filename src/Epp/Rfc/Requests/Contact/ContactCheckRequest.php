<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests\Contact;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Commands\Check;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactCheck;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactId;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;

class ContactCheckRequest extends Request
{
    /** @var array<string> */
    private $contacts;

    public function __construct(array $contacts)
    {
        parent::__construct();

        $this->contacts = $contacts;
    }

    protected function renderElements(): DOMElement
    {
        return Epp::render([
            Command::render([
                Check::render([
                    ContactCheck::render(
                        array_map(function (string $domain) {
                            return ContactId::render([], $domain);
                        }, $this->contacts)
                    ),
                ]),
                $this->renderExtension(),
                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ], null, $this->namespaces);
    }
}
