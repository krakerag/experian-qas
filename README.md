experian-qas - PHP library to interface with Experian QAS
==============================

This library is designed to provide a class interface to the Experian QAS postcode search product.

It is built with the SOAP Pro Web product in mind and tested with GBR datasets primarily.

Experian QAS can be found via it's website at http://www.qas-experian.com.au/ or http://www.qas.co.uk/

Installation
------------

Install via packagist/composer: https://packagist.org/packages/krakerag/experian-qas

Usage
-----

```php

use \krakerag\ExperianQas\PostcodeSearch\PostcodeSearch;
use \krakerag\ExperianQas\Engine\Engine;

$wsdl = 'http://blah.com/QAPro/Pro.wsdl';

$engine = new Engine;
$search = new PostcodeSearch(new \Psr\Log\NullLogger(), $wsdl);
$search->setPostcode('SW40QB');
$results = $search->find();

var_dump($results); // etc

```

About
=====

Requirements
------------

- PHP 5.3 or higher
- [optional] PHPUnit 3.5+ to execute the test suite (phpunit --version)

Submitting bugs and feature requests
------------------------------------

Bugs and feature requests are tracked on [GitHub](https://github.com/krakerag/experian-qas/issues)

Author
------

Matthew Hallsworth - <matthew.hallsworth@gmail.com>

License
-------

This is licensed under the MIT License - see the `LICENSE.txt` file for details
