<?php

namespace Tests\TestFramework\Traits;

use SoapClient;
use stdClass;
use Tests\TestFramework\Fake\FakeSoapClient;

trait FakesSoapServiceTrait
{
    /** @var FakeSoapClient $fakeSoapClient */
    protected $fakeSoapClient;

    protected function mockSoapClient($url)
    {
        $this->fakeSoapClient = new FakeSoapClient($url);
        app()->bind(SoapClient::class, function () use ($url) {
            return $this->fakeSoapClient;
        });
    }

    public function setSoapTestData()
    {
        $this->addDummyData(
            'findCustomer',
            file_get_contents(base_path('tests/dummydata/soapFindCustomer.xml')),
            new stdClass()
        );

        $createOrUpdateCustomerResult = new stdClass();
        $createOrUpdateCustomerResult->account = 123;
        $this->addDummyData(
            'createOrUpdateCustomer',
            file_get_contents(base_path('tests/dummydata/soapCreateOrUpdateCustomer.xml')),
            $createOrUpdateCustomerResult
        );

        $checkOrCreateOrderResult = new stdClass();
        $checkOrCreateOrderResult->orderNumber = 456;
        $this->addDummyData(
            'checkOrCreateOrder',
            file_get_contents(base_path('tests/dummydata/soapCheckOrCreateOrderRequest.xml')),
            file_get_contents(base_path('tests/dummydata/soapCheckOrCreateOrderRespone.xml')),
            $checkOrCreateOrderResult
        );
    }

    public function addDummyData($name, $request, $response, $result)
    {
        $this->fakeSoapClient->addDummyData($name, $request, $response, $result);
    }

    public function setSoapRequest($name, $request)
    {
        $this->fakeSoapClient->setDummyRequest($name, $request);
    }

    public function setSoapResponse($name, $response)
    {
        $this->fakeSoapClient->setDummyResponse($name, $response);
    }

    public function setSoapResult($name, $result)
    {
        $this->fakeSoapClient->setDummyResult($name, $result);
    }

    public function assertArgumentsOfNextCall($name, $arguments)
    {
        $this->fakeSoapClient->assertArgumentsOfNextCall($name, $arguments);
    }
}
