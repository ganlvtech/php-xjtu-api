# PHP XJTU API

API for Xi'an Jiao Tong University's commonly used online service by simulating operation.

## Installation

Simply add a dependency to `composer.json`. Here is an example:

    {
        "require": {
            "ganlvtech/php-xjtu-api": "~1.0"
        }
    }

## Usage

```php
<?php
use XjtuApi\XjtuNetTraffic;

require 'vendor/autoload.php';

$xjtuNetTraffic = new XjtuNetTraffic();
$response = $xjtuNetTraffic->login('username', 'password');
var_dump($response);
$response = $xjtuNetTraffic->current();
var_dump($response);
$response = $xjtuNetTraffic->logout();
var_dump($response);
```

## Modules

* XjtuNetTraffic
* XjtuCas
