<?php

namespace krakerag\ExperianQas\Engine;

/**
 * Class Engine
 * @package krakerag\ExperianQas\Engine
 */
class Engine {

    /**
     * @var string
     */
    private $flatten = 'true';

    /**
     * @var string
     */
    private $intensity = 'Exact';

    /**
     * @var string
     */
    private $promptSet = 'Optimal';

    /**
     * @var int
     */
    private $threshold = 25;

    /**
     * @var int
     */
    private $timeout = 1;

    /**
     * @var string
     */
    private $_ = 'Singleline';

    /**
     * @param string $engine
     */
    public function setEngine($engine)
    {
        $this->_ = $engine;
    }

    /**
     * @return string
     */
    public function getEngine()
    {
        return $this->_;
    }

    /**
     * @param string $flatten
     */
    public function setFlatten($flatten)
    {
        $this->flatten = $flatten;
    }

    /**
     * @return string
     */
    public function getFlatten()
    {
        return $this->flatten;
    }

    /**
     * @param string $intensity
     */
    public function setIntensity($intensity)
    {
        $this->intensity = $intensity;
    }

    /**
     * @return string
     */
    public function getIntensity()
    {
        return $this->intensity;
    }

    /**
     * @param string $promptSet
     */
    public function setPromptSet($promptSet)
    {
        $this->promptSet = $promptSet;
    }

    /**
     * @return string
     */
    public function getPromptSet()
    {
        return $this->promptSet;
    }

    /**
     * @param int $threshold
     */
    public function setThreshold($threshold)
    {
        $this->threshold = $threshold;
    }

    /**
     * @return int
     */
    public function getThreshold()
    {
        return $this->threshold;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }
}