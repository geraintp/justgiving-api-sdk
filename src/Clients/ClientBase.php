<?php namespace Klever\JustGivingApiSdk\Clients;

use Klever\JustGivingApiSdk\Clients\Http\CurlWrapper;

class HTTPResponse
{
    public $httpStatusCode;
    public $bodyResponse;
}


class ClientBase
{
    public $debug;
    public $Parent;
    public $curlWrapper;

    public function __construct($justGivingApi)
    {
        $this->Parent = $justGivingApi;
        $this->curlWrapper = new CurlWrapper();
        $this->debug = false;
    }

    public function BuildUrl($locationFormat)
    {
        $url = $locationFormat;
        $url = str_replace("{apiKey}", $this->Parent->ApiKey, $url);
        $url = str_replace("{apiVersion}", $this->Parent->ApiVersion, $url);

        return $url;
    }

    public function BuildAuthenticationValue()
    {
        if ($this->Parent->Username != null && $this->Parent->Username != "") {
            $stringForEnc = $this->Parent->Username . ":" . $this->Parent->Password;

            return base64_encode($stringForEnc);
        }

        return "";
    }

    public function WriteLine($string)
    {
        echo $string . "<br/>";
    }
}
