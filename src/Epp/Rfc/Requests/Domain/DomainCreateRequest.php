<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests\Domain;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Commands\Create;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainAuthInfo;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainContact;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainCreate;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainHostObject;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainName;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainNameservers;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainPassword;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainPeriod;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainRegistrant;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;

class DomainCreateRequest extends Request
{
    /** @var string */
    private $domain;

    /** @var int|null */
    private $period;

    /** @var array|null */
    private $nameservers;

    /** @var string|null */
    private $registrant;

    /** @var array|null */
    private $contacts;

    /** @var string|null */
    private $password;

    /**
     * DomainCreateRequest constructor.
     *
     * @param string                    $domain
     * @param int|null                  $period
     * @param array<string>|null        $nameservers
     * @param string|null               $registrant
     * @param array<string,string>|null $contacts    Key: type, Value: contact. Example: ['admin' => 'sh8013', 'tech' => 'sh8013'].
     * @param string|null               $password
     */
    public function __construct(
        string $domain,
        ?int $period = null,
        ?array $nameservers = null,
        ?string $registrant = null,
        ?array $contacts = null,
        ?string $password = null
    ) {
        parent::__construct();

        $this->domain = $domain;
        $this->period = $period;
        $this->nameservers = $nameservers;
        $this->registrant = $registrant;
        $this->contacts = $contacts;
        $this->password = $password;
    }

    protected function renderElements(): DOMElement
    {
        return Epp::render([
            Command::render([
                Create::render([
                    DomainCreate::render(array_merge(
                        [
                            DomainName::render([], $this->domain),
                            $this->period ? DomainPeriod::render([], (string) $this->period, ['unit' => 'y']) : null,

                            $this->nameservers ? DomainNameservers::render(
                                array_map(function (string $nameserver) {
                                    return DomainHostObject::render([], $nameserver);
                                }, $this->nameservers)
                            ) : null,

                            $this->registrant ? DomainRegistrant::render([], $this->registrant) : null,
                        ],
                        $this->renderContacts(),
                        [
                            $this->password ? DomainAuthInfo::render([
                                DomainPassword::render([], $this->password),
                            ]) : null,
                        ]
                    )),
                ]),

                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ], null, $this->extensions);
    }

    private function renderContacts(): array
    {
        return $this->contacts
            ? array_map(function (string $type, string $contact) {
                return DomainContact::render([], $contact, ['type' => $type]);
            }, array_keys($this->contacts), $this->contacts)
            : [];
    }
}
