<?php

namespace krakerag\ExperianQas\PostcodeSearch;

class EngineTest extends \PHPUnit_Framework_TestCase {

    public function testClassInstantiation()
    {
        $engine = new Engine();
    }

    public function testSetGetEngine()
    {
        $engine = new Engine();
        $this->assertEquals('true', $engine->getFlatten());
        $this->assertEquals('Exact', $engine->getIntensity());
        $this->assertEquals('Optimal', $engine->getPromptSet());
        $this->assertEquals(25, $engine->getThreshold());
        $this->assertEquals(1, $engine->getTimeout());
        $this->assertEquals('Singleline', $engine->getEngine());

        $engine->setFlatten('false');
        $engine->setIntensity('Close');
        $engine->setPromptSet('Generic');
        $engine->setThreshold(50);
        $engine->setTimeout(20);
        $engine->setEngine('Verification');

        $this->assertEquals('false', $engine->getFlatten());
        $this->assertEquals('Close', $engine->getIntensity());
        $this->assertEquals('Generic', $engine->getPromptSet());
        $this->assertEquals(50, $engine->getThreshold());
        $this->assertEquals(20, $engine->getTimeout());
        $this->assertEquals('Verification', $engine->getEngine());
    }

}
