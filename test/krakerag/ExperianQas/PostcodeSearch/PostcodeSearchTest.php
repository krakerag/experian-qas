<?php

namespace krakerag\ExperianQas\PostcodeSearch;

use krakerag\ExperianQas\PostcodeSearch\Engine;
use krakerag\ExperianQas\PostcodeSearch\PostcodeSearch;

class PostcodeSearchTest extends \PHPUnit_Framework_TestCase {

    public function testClassInstantiation()
    {
        $search = new PostcodeSearch(new \Psr\Log\NullLogger(), 'http://www.test.org/?wsdl');
    }

    public function testSoapCall()
    {
        $wsdl = __DIR__.'/default.wsdl';

        $engine = new Engine();
        $search = new PostcodeSearch(new \Psr\Log\NullLogger(), $wsdl);
        $search->setEngine($engine);
        try {
            $results = $search->find('GBR','SW40QB');
            $this->fail('Should fail with can\'t connect to host');

        } catch (\Exception $exception) {

        }
    }

    public function testSetGetSoapClient()
    {
        $wsdl = __DIR__.'/default.wsdl';

        $engine = new Engine();
        $search = new PostcodeSearch(new \Psr\Log\NullLogger(), $wsdl);
        $search->setEngine($engine);

        // Create mock object for SoapClient
        $mockSoapClient = $this->getMockBuilder('SoapClient')
            ->setMethods(array('DoSearch'))
            ->disableOriginalConstructor()
            ->getMock();
        $mockSoapClient->expects($this->once())->method('DoSearch')->will($this->returnValue(array()));

        try {
            $mockSoapClient->DoSearch(array());

        } catch (\Exception $exception) {
            $this->fail('For some reason the mock soap object returned something other than an array()');
        }
    }


}
