<?php

namespace SandwaveIo\EppClient\Epp\Extensions\Requests\Sidn;

use DOMElement;
use SandwaveIo\EppClient\Epp\Extensions\Elements\Sidn\SidnContact;
use SandwaveIo\EppClient\Epp\Extensions\Elements\Sidn\SidnCreate;
use SandwaveIo\EppClient\Epp\Extensions\Elements\Sidn\SidnExt;
use SandwaveIo\EppClient\Epp\Extensions\Elements\Sidn\SidnLegalForm;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Extension;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Contact\ContactCreateRequest;

class SidnContactCreateRequest extends ContactCreateRequest
{
    /**
     * <extension>
     *   <sidn-ext-epp:ext>
     *     <sidn-ext-epp:create>
     *       <sidn-ext-epp:contact>
     *         <sidn-ext-epp:legalForm>PERSOON</sidn-ext-epp:legalForm>
     *       </sidn-ext-epp:contact>
     *     </sidn-ext-epp:create>
     *   </sidn-ext-epp:ext>
     * </extension>
     */
    public function renderExtension(): ?DOMelement
    {
        $isBusiness = false;

        if ($this->internationalAddress && $this->internationalAddress->organization) {
            $isBusiness = true;
        } elseif ($this->localAddress && $this->localAddress->organization) {
            $isBusiness = true;
        }

        $form = $isBusiness ? SidnLegalForm::LEGAL_FORM_BUSINESS : SidnLegalForm::LEGAL_FORM_PRIVATE;

        return Extension::render([
            SidnExt::render([
                SidnCreate::render([
                    SidnContact::render([
                        SidnLegalForm::render([], $form)
                    ])
                ])
            ])
        ]);
    }
}