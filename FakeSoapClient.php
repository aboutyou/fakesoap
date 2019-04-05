<?php

namespace Tests\TestFramework\Fake;

use SoapClient;

class FakeSoapClient extends SoapClient
{
    protected $dummyResponses = [];
    protected $dummyRequests = [];
    protected $dummyResults = [];
    protected $lastRequest;
    protected $fakeUrl;
    protected $fakeParams;
    protected $lastResponse;
    protected $nextCallArguments = [];

    public function __call($name, $arguments)
    {
        if (isset($this->dummyResults[$name])) {
            if (isset($this->dummyResponses[$name])) {
                if (isset($this->dummyRequests)) {
                    $this->setLastRequest($this->dummyRequests[$name]);
                }
                $this->setLastResponse($this->dummyResponses[$name]);
            }
            return $this->dummyResults[$name];
        }
        return null;
    }

    public function __construct($fakeUrl, array $fakeParams = array())
    {
        $this->fakeUrl = $fakeUrl;
        $this->fakeParams = $fakeParams;
    }

    public function __soapCall(
        $function_name,
        $arguments,
        $options = null,
        $input_headers = null,
        &$output_headers = null
    ) {
        if (isset($this->nextCallArguments[$function_name])) {
            \TestCase::assertArraySubset($this->nextCallArguments[$function_name], $arguments);
            unset($this->nextCallArguments[$function_name]);
        }
        if (isset($this->dummyResults[$function_name])) {
            if (isset($this->dummyResponses[$function_name])) {
                if (isset($this->dummyRequests)) {
                    $this->setLastRequest($this->dummyRequests[$function_name]);
                }
                $this->setLastResponse($this->dummyResponses[$function_name]);
            }
            return $this->dummyResults[$function_name];
        }
        return null;
    }

    public function setDummyResponses(array $dummyResponses)
    {
        $this->dummyResponses = $dummyResponses;
    }

    public function addDummyData($name, $request, $response, $result)
    {
        $this->dummyResponses[$name] = $response;
        $this->dummyRequests[$name] = $request;
        $this->dummyResults[$name] = $result;
    }

    public function setLastRequest($lastRequest)
    {
        $this->lastRequest = $lastRequest;
    }

    public function __getLastRequest()
    {
        return $this->lastRequest;
    }

    public function setLastResponse($lastResponse)
    {
        $this->lastResponse = $lastResponse;
    }

    public function __getLastResponse()
    {
        return $this->lastResponse;
    }

    public function setDummyResponse($name, $dummyResponse)
    {
        $this->dummyResponses[$name] = $dummyResponse;
    }

    public function setDummyRequest($name, $dummyRequest)
    {
        $this->dummyRequests[$name] = $dummyRequest;
    }

    public function setDummyResult($name, $dummyResult)
    {
        $this->dummyReesults[$name] = $dummyResult;
    }

    public function assertArgumentsOfNextCall($name, $argumentsContain)
    {
        $this->nextCallArguments[$name] = $argumentsContain;
    }
}
