<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\ConnectionDriver;

class HttpsConnectionDriver extends HttpConnectionDriver
{
    /** @return resource */
    protected function makeCurlRequest()
    {
        $curl = parent::makeCurlRequest();
        curl_setopt($curl, CURLOPT_URL, "https://{$this->hostname}");

        if ($this->localCertificatePath) {
            curl_setopt($curl, CURLOPT_SSLCERT, $this->localCertificatePath);
            curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $this->localCertificatePassword);
        } else {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        }

        return $curl;
    }
}
