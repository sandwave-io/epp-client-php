<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Services;

use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;

class SidnService extends AbstractService
{
    protected function request(Request $request, ?string $transactionId = null): Document
    {
        $request->addEppExtension('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        return parent::request($request, $transactionId);
    }
}
