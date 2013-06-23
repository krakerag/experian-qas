<?php

namespace krakerag\ExperianQas\PostcodeSearch;

class EngineTest extends \PHPUnit_Framework_TestCase {

    public function testClassInstantiation()
    {
        $search = new PostcodeSearch(new \Psr\Log\NullLogger(), 'http://www.test.org/?wsdl');
    }

}