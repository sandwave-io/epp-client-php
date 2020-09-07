<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\DomainInfo\DomainAuthInfo;
use SandwaveIo\EppClient\Epp\Rfc\Elements\DomainInfo\DomainInfo;
use SandwaveIo\EppClient\Epp\Rfc\Elements\DomainInfo\DomainName;
use SandwaveIo\EppClient\Epp\Rfc\Elements\DomainInfo\DomainPassword;
use SandwaveIo\EppClient\Epp\Rfc\Elements\DomainInfo\Info;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;
use Webmozart\Assert\Assert;

class DomainInfoRequest extends Request
{
    const FILTER_ALL = 'all';
    const FILTER_DEL = 'del';
    const FILTER_SUB = 'sub';
    const FILTER_NONE = 'none';

    /** @var string */
    private $domain;

    /** @var string */
    private $hostsFilter;

    /** @var string|null */
    private $password;

    public function __construct(string $domain, string $hostsFilter = DomainInfoRequest::FILTER_ALL, ?string $password = null)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->hostsFilter = $hostsFilter;
        $this->password = $password;

        Assert::inArray($hostsFilter, [
            DomainInfoRequest::FILTER_ALL,
            DomainInfoRequest::FILTER_DEL,
            DomainInfoRequest::FILTER_SUB,
            DomainInfoRequest::FILTER_NONE,
        ]);
    }

    protected function renderElements(): DOMElement
    {
        return Epp::render([
            Command::render([
                Info::render([
                    DomainInfo::render([
                        DomainName::render([], $this->domain, ['hosts' => $this->hostsFilter]),
                        $this->renderPassword(),
                    ]),
                ]),
                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ], null, $this->extensions);
    }

    private function renderPassword(): ?DOMElement
    {
        if (! $this->password) {
            return null;
        }

        return DomainAuthInfo::render([
            DomainPassword::render([], $this->password),
        ]);
    }
}