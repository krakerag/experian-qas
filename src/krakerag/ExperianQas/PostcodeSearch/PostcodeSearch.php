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
     * @var string
     */
    private $country = 'GBR';

    /**
     * @var null
     */
    private $engine = null;

    /**
     * @var null
     */
    private $postcode = null;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(\Psr\Log\LoggerInterface $logger, $wsdl)
    {
        $this->logger = $logger;
        $this->wsdl = $wsdl;
    }

    public function find()
    {
        if (is_null($this->postcode)) {
            throw new \InvalidArgumentException('Postcode must be set');
        }

        if (is_null($this->soapClient)) {
            $this->generateSoapClient();
        }

        try {

            $client = $this->soapClient;

            $results = $client->DoSearch(
                array(
                    'Country' => $this->country,
                    'Engine' => array(
                        '_' => $this->engine->getEngine(),
                        'Flatten' => $this->engine->getFlatten(),
                        'Intensity' => $this->engine->getIntensity(),
                        'PromptSet' => $this->engine->getPromptSet(),
                        'Threshold' => $this->engine->getThreshold(),
                        'Timeout' => $this->engine->getTimeout(),
                    ),
                    'Search' => $this->postcode,
                )
            );

            // Parse results and return ArrayIterator set
            foreach ($results->QAPicklist as $result) {
                // Only valid results are a single object or an array of objects
                if (is_object($result) || is_array($result)) {
                    return $this->parseResult($result);
                }
            }

        } catch (\SoapFault $fault) {

            $this->logger->log('SoapFault thrown when attempting to fetch postcode data');
            $this->logger->log('Last request: '.$client->__getLastRequest());
            $this->logger->log('Last response: '.$client->__getLastResponse());
            throw new \Exception('Call to QASearch failed with an exception: '.$fault->getMessage());
        }
    }

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
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
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
     * @param null $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    /**
     * @return null
     */
    public function getPostcode()
    {
        return $this->postcode;
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
