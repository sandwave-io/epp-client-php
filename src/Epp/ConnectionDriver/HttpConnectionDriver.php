<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\ConnectionDriver;

use CurlHandle;
use SandwaveIo\EppClient\Exceptions\ConnectException;

class HttpConnectionDriver extends AbstractConnectionDriver
{
    public function executeRequest(string $request, string $requestId): string
    {
        $curl = $this->makeCurlRequest();

        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        $response = curl_exec($curl);

        $error = curl_errno($curl);

        if ($error) {
            throw new ConnectException(sprintf('Error occurred while executing CURL %d: %s', $error, curl_error($curl)));
        }

        if (! is_string($response)) {
            return '';
        }

        return $response;
    }

    public function connect(): bool
    {
        return true;
    }

    public function disconnect(): bool
    {
        return true;
    }

    protected function makeCurlRequest(): CurlHandle
    {
        $curl = curl_init("http://{$this->hostname}");
        assert($curl instanceof CurlHandle, 'Cannot create curl request');

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_COOKIE, true);
        curl_setopt($curl, CURLOPT_COOKIEFILE, tmpfile());
        return $curl;
    }
}
