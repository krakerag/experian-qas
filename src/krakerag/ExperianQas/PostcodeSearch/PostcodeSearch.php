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
            $engine = new \SoapVar($this->engine, SOAP_ENC_OBJECT, 'Engine');
            if (method_exists($client, 'QASearch')) {
                $results = $client->QASearch(
                    array(
                        'Country' => $this->country,
                        'Engine' => $engine,
                        'Search' => $this->postcode,
                    )
                );

                var_dump($results);

                return true;
            } else {
                throw new \ErrorException('QASearch does not exist as a method against the WSDL provided');
            }

        } catch (\SoapFault $fault) {
            throw new Exception('Call to QASearch failed with an exception: '.$fault->getMessage());
        }
    }

    /**
     * Generate a default SOAP client which is compatible with the class mapping required
     */
    private function generateSoapClient()
    {
        $client = new \SoapClient($this->wsdl, array(
            'encoding'     => 'UTF-8',
            'soap_version' => SOAP_1_1,
            'classmap'     => array('Engine' => 'Engine')
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