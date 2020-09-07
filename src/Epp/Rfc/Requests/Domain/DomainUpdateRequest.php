<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests\Domain;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Commands\Update;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainAdd;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainAuthInfo;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainChg;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainContact;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainHostObject;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainName;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainNameservers;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainPassword;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainRegistrant;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainRem;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainStatus;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainUpdate;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;

class DomainUpdateRequest extends Request
{
    /** @var string */
    private $domain;

    /** @var array<string>|null */
    private $addNameservers;

    /** @var array<string,string>|null */
    private $addContacts;

    /** @var array<string,string>|null */
    private $addStatuses;

    /** @var array<string>|null */
    private $removeNameservers;

    /** @var array<string,string>|null */
    private $removeContacts;

    /** @var array<string,string>|null */
    private $removeStatuses;

    /** @var string|null */
    private $changeRegistrant;

    /** @var string|null */
    private $changePassword;

    /**
     * DomainUpdateRequest constructor.
     *
     * @param string                    $domain
     * @param array<string>|null        $addNameservers
     * @param array<string,string>|null $addContacts
     * @param array<string,string>|null $addStatuses
     * @param array<string>|null        $removeNameservers
     * @param array<string,string>|null $removeContacts
     * @param array<string,string>|null $removeStatuses
     * @param string|null               $changeRegistrant
     * @param string|null               $changePassword
     */
    public function __construct(
        string $domain,
        ?array $addNameservers = null,
        ?array $addContacts = null,
        ?array $addStatuses = null,
        ?array $removeNameservers = null,
        ?array $removeContacts = null,
        ?array $removeStatuses = null,
        ?string $changeRegistrant = null,
        ?string $changePassword = null
    ) {
        parent::__construct();

        $this->domain = $domain;
        $this->addNameservers = $addNameservers;
        $this->addContacts = $addContacts;
        $this->addStatuses = $addStatuses;
        $this->removeNameservers = $removeNameservers;
        $this->removeContacts = $removeContacts;
        $this->removeStatuses = $removeStatuses;
        $this->changeRegistrant = $changeRegistrant;
        $this->changePassword = $changePassword;
    }

    protected function renderElements(): DOMElement
    {
        return Epp::render([
            Command::render([
                Update::render([
                    DomainUpdate::render([
                        DomainName::render([], $this->domain),
                        $this->renderAdditions(),
                        $this->renderRemovals(),
                        $this->renderChanges(),
                    ]),
                ]),

                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ], null, $this->extensions);
    }

    private function renderAdditions(): ?DOMElement
    {
        $additions = [];

        if ($this->addNameservers) {
            $additions[] = DomainNameservers::render(array_map(function (string $ns) {
                return DomainHostObject::render([], $ns);
            }, $this->addNameservers));
        }
        if ($this->addContacts) {
            foreach ($this->addContacts as $type => $contact) {
                $additions[] = DomainContact::render([], $contact, ['type' => $type]);
            }
        }
        if ($this->addStatuses) {
            foreach ($this->addStatuses as $status => $description) {
                $additions[] = DomainStatus::render([], $description, ['s' => $status, 'lang' => 'en']);
            }
        }

        return count($additions) === 0 ? null : DomainAdd::render($additions);
    }

    private function renderRemovals(): ?DOMElement
    {
        $removals = [];

        if ($this->removeNameservers) {
            $removals[] = DomainNameservers::render(array_map(function (string $ns) {
                return DomainHostObject::render([], $ns);
            }, $this->removeNameservers));
        }
        if ($this->removeContacts) {
            foreach ($this->removeContacts as $type => $contact) {
                $removals[] = DomainContact::render([], $contact, ['type' => $type]);
            }
        }
        if ($this->removeStatuses) {
            foreach ($this->removeStatuses as $status => $description) {
                $removals[] = DomainStatus::render([], $description, ['s' => $status]);
            }
        }

        return count($removals) === 0 ? null : DomainRem::render($removals);
    }

    private function renderChanges(): ?DOMElement
    {
        $changes = [];

        if ($this->changeRegistrant) {
            $changes[] = DomainRegistrant::render([], $this->changeRegistrant);
        }
        if ($this->changePassword) {
            $changes[] = DomainAuthInfo::render([
                DomainPassword::render([], $this->changePassword),
            ]);
        }

        return count($changes) === 0 ? null : DomainChg::render($changes);
    }
}
