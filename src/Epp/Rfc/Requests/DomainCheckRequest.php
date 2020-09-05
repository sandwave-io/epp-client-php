<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\DomainCheck\Check;
use SandwaveIo\EppClient\Epp\Rfc\Elements\DomainCheck\DomainCheck;
use SandwaveIo\EppClient\Epp\Rfc\Elements\DomainCheck\DomainName;

class DomainCheckRequest extends Request
{
    /** @var array<string> */
    private $domains;

    public function __construct(array $domains, array $extensions = [], ?string $clientTransactionIdentifier = null)
    {
        parent::__construct($clientTransactionIdentifier, $extensions);

        $this->domains = $domains;
    }

    protected function renderElements(): DOMElement
    {
        return $this->renderEppElement([
            Command::render([
                Check::render([
                    DomainCheck::render(
                        array_map(function (string $domain) {
                            return DomainName::render([], $domain);
                        }, $this->domains)
                    ),
                ]),
                ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier),
            ]),
        ]);
    }
}
