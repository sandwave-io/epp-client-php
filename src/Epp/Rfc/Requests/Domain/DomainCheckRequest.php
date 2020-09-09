<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests\Domain;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Commands\Check;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainCheck;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainName;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;

class DomainCheckRequest extends Request
{
    /** @var array<string> */
    private $domains;

    public function __construct(array $domains)
    {
        parent::__construct();

        $this->domains = $domains;
    }

    protected function renderElements(): DOMElement
    {
        return Epp::render([
            Command::render([
                Check::render([
                    DomainCheck::render(
                        array_map(function (string $domain) {
                            return DomainName::render([], $domain);
                        }, $this->domains)
                    ),
                ]),
                $this->renderExtension(),
                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ], null, $this->extensions);
    }
}
