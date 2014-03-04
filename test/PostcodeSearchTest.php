<?php

namespace krakerag\ExperianQas\PostcodeSearch;

use krakerag\ExperianQas\PostcodeSearch\Engine;
use krakerag\ExperianQas\PostcodeSearch\PostcodeSearch;

class PostcodeSearchTest extends \PHPUnit_Framework_TestCase {

    public function testSoapCall()
    {
        $wsdl = __DIR__.'/default.wsdl';

        $engine = new Engine();
        $search = new PostcodeSearch(new \Psr\Log\NullLogger(), $wsdl);
        $search->setEngine($engine);
        try {
            $search->find('GBR','SW40QB');
            $this->fail('Should fail with can\'t connect to host');

        } catch (\Exception $exception) {

        }
    }

    public function testGetEmptyMockFromWsdl()
    {
        $wsdl = __DIR__.'/default.wsdl';

        $mockObject = $this->getMockFromWsdl($wsdl, 'ProWeb');
        $mockObject->expects($this->any())
            ->method('DoSearch')
            ->will($this->returnValue($this->standardReturnEmptyObject()));

        $engine = new Engine();
        $search = new PostcodeSearch(new \Psr\Log\NullLogger(), $wsdl);
        $search->setEngine($engine);
        $search->setSoapClient($mockObject);

        $response = $search->find('GBR','AB123');
        $this->assertNull($response, 'No postcode information returned so should get a null');
    }

    public function testGetSingleResult()
    {
        $wsdl = __DIR__.'/default.wsdl';

        $mockObject = $this->getMockFromWsdl($wsdl, 'ProWeb');
        $mockObject->expects($this->any())
            ->method('DoSearch')
            ->will($this->returnValue($this->standardReturnValidSingleObject()));

        $engine = new Engine();
        $search = new PostcodeSearch(new \Psr\Log\NullLogger(), $wsdl);
        $search->setEngine($engine);
        $search->setSoapClient($mockObject);

        $result = $search->find('GBR','AB123');
        $this->assertCount(0, $result, 'Should get an empty array');

    }

    public function testGetMultipleResult()
    {
        $wsdl = __DIR__.'/default.wsdl';

        $mockObject = $this->getMockFromWsdl($wsdl, 'ProWeb');
        $mockObject->expects($this->any())
            ->method('DoSearch')
            ->will($this->returnValue($this->standardReturnValidMultipleObjects()));

        $engine = new Engine();
        $search = new PostcodeSearch(new \Psr\Log\NullLogger(), $wsdl);
        $search->setEngine($engine);
        $search->setSoapClient($mockObject);

        $result = $search->find('GBR','AB123');
        $this->assertCount(3, $result, 'Should get an empty array');

    }

    /**
     * Test stub for returning an empty dataless object
     *
     * @return \stdClass
     */
    private function standardReturnEmptyObject()
    {
        $response = new \stdClass();
        $response->QAPicklist = array();

        return $response;
    }

    /**
     * Test stub for returning a single address
     *
     * @return \stdClass
     */
    private function standardReturnValidSingleObject()
    {
        $response = new \stdClass();

        $qaPickList = new \stdClass();
        $qaPickList->Moniker = "";
        $qaPickList->Postcode = "";

        $response->QAPicklist = array($qaPickList);

        return $response;
    }

    /**
     * Test stub for returning a multiple address object
     *
     * @return \stdClass
     */
    private function standardReturnValidMultipleObjects()
    {
        $response = new \stdClass();

        $qaPickList = new \stdClass();
        $qaPickList->Moniker = "Multiple matches";
        $qaPickList->Postcode = "1234";

        $response->QAPicklist = array(
            array(
                'address1' => "Test address 1",
                'address2' => "Test address 2",
                'address3' => "Test address 3",
            )
        );

        return $response;
    }

}
