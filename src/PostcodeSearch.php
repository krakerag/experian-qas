<?php

namespace krakerag\ExperianQas\PostcodeSearch;

/**
 * Class PostcodeSearch
 * @package krakerag\ExperianQas\PostcodeSearch
 */
class PostcodeSearch {

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger = null;

    /**
     * @var \SoapClient
     */
    private $soapClient = null;

    /**
     * @var null
     */
    private $wsdl = null;

    /**
     * @var null
     */
    private $engine = null;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(\Psr\Log\LoggerInterface $logger, $wsdl)
    {
        $this->logger = $logger;
        $this->wsdl = $wsdl;
    }

    /**
     * @param $country
     * @param $postcode
     * @return array
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function find($country, $postcode)
    {
        if (is_null($postcode)) {
            throw new \InvalidArgumentException('Postcode must be set');
        }
        if (is_null($country)) {
            throw new \InvalidArgumentException('Country must be set');
        }
        if (strlen($country) != 3) {
            throw new \InvalidArgumentException('Country must be 3 characters');
        }
        if (is_null($this->engine)) {
            throw new \InvalidArgumentException('Engine must be an instance of the Engine object');
        }

        if (is_null($this->soapClient)) {
            $this->generateSoapClient();
        }

        try {

            /** @var \SoapClient $client */
            $client = $this->soapClient;
            /** @var Engine $engine */
            $engine = $this->engine;

            /** @var mixed $results */
            $results = $client->DoSearch(
                array(
                    'Country' => $country,
                    'Engine' => array(
                        '_' => $engine->getEngine(),
                        'Flatten' => $engine->getFlatten(),
                        'Intensity' => $engine->getIntensity(),
                        'PromptSet' => $engine->getPromptSet(),
                        'Threshold' => $engine->getThreshold(),
                        'Timeout' => $engine->getTimeout(),
                    ),
                    'Search' => $postcode,
                )
            );

            // Parse results and return ArrayIterator set
            foreach ($results->QAPicklist as $result) {
                // Only valid results are a single object or an array of objects
                if (is_object($result) || is_array($result)) {
                    return $this->parseResult($result);
                }
            }

            // If we have no results we return null
            return null;

        } catch (\SoapFault $fault) {

            $this->logger->error('SoapFault thrown when attempting to fetch postcode data');
            $this->logger->error('Last request: '.$client->__getLastRequest());
            $this->logger->error('Last response: '.$client->__getLastResponse());
            throw new \Exception('Call to QASearch failed with an exception: '.$fault->getMessage());
        }
    }

    /**
     * @param $result
     * @return array
     */
    private function parseResult($result)
    {
        $parsedResults = array();

        if (is_object($result) && $result->Moniker == "" && $result->Postcode == "") {
            return $parsedResults;
        }

        if (is_array($result) && count($result) > 0) {
            foreach($result as $address) {
                $parsedResultsItem = (array)$address;
                $parsedResults[] = $parsedResultsItem; 
            }
        }

        return $parsedResults;
    }

    /**
     * Generate a default SOAP client which is compatible with the class mapping required
     */
    private function generateSoapClient()
    {
        $client = new \SoapClient($this->wsdl, array(
            'trace' => 1,
            'encoding' => 'UTF-8',
            'soap_version' => SOAP_1_1,
        ));

        $this->soapClient = $client;
    }

    /**
     * @param null $engine
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;
    }

    /**
     * @return null
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param \SoapClient $soapClient
     */
    public function setSoapClient($soapClient)
    {
        $this->soapClient = $soapClient;
    }

    /**
     * @return \SoapClient
     */
    public function getSoapClient()
    {
        return $this->soapClient;
    }

    /**
     * @param null $wsdl
     */
    public function setWsdl($wsdl)
    {
        $this->wsdl = $wsdl;
    }

    /**
     * @return null
     */
    public function getWsdl()
    {
        return $this->wsdl;
    }



}
