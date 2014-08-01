Manager
===

[![Build Status](https://travis-ci.org/MageTest/Manager.svg?branch=master)](https://travis-ci.org/MageTest/Manager)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/MageTest/Manager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/MageTest/Manager/?branch=master)

Manager is a PHP library that manages test fixtures for a Magento site.

Fixtures
---
Manager takes YAML files as an input to create fixtures for a Magento test environment.

A YAML file maps to a Magento model via the key of the collection. The collection then maps to the model attributes
of the defined Magento model.

Defining a model in a YAML file would look like this:
``` yaml
customer/customer:
    firstname: test
    lastname: test
    email: customer@example.com
    password: 123123pass
    website_id: 1
    store: 1
    status: 1
```
The most important part, is the key in the collection. This is the argument supplied to `Mage::getModel()`.
After we define the fixture, all we need to do now is load it into the Manager.

``` php
<?php
use MageTest\Manager\FixtureManager,
    MageTest\Manager\Attributes\Provider\YamlProvider;

//init Manager and define attributes provider. Default is YAML
$manager = new FixtureManager(new YamlProvider());

$yamlFile = 'src/MageTest/Manager/Fixtures/Customer.yml';

//Load fixture into
$manager->loadFixture($yamlFile);

//Use key defined in fixture file, to return instance of fixture model
$customer = $manager->getFixture('customer/customer');

//Use customer model, change values/behaviour, assert data for acceptance tests

//Delete all fixtures from Magento test environment DB
$manager->clear();
```
Usage
---
This library can be used in conjunction with Behat (or any other acceptance/functional testing tool of choice).
The flow could look something like this:
- Instantiate Manager before the test suite.
- Before a feature, load required model(s) for acceptance test
- After the suite, call `clear()` to clean up fixtures.

The aim is to keep the Step Defintions slim, and abstract away the DB interactions required to set up test data
(think what Mink does as a web browser emulator abstraction).

Roadmap
---
- Default model builder.
- Add support for Configurable products, Bundled products
- Handle multiple instances of the same fixture.
- JSON, XML attribute providers.

Contributors
---
Authors: https://github.com/MageTest/Manager/contributors
