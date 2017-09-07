# test-util

[![Build Status](https://travis-ci.org/localheinz/test-util.svg?branch=master)](https://travis-ci.org/localheinz/test-util)
[![Code Climate](https://codeclimate.com/github/localheinz/test-util/badges/gpa.svg)](https://codeclimate.com/github/localheinz/test-util)
[![Test Coverage](https://codeclimate.com/github/localheinz/test-util/badges/coverage.svg)](https://codeclimate.com/github/localheinz/test-util/coverage)
[![Issue Count](https://codeclimate.com/github/localheinz/test-util/badges/issue_count.svg)](https://codeclimate.com/github/localheinz/test-util)
[![Latest Stable Version](https://poser.pugx.org/localheinz/test-util/v/stable)](https://packagist.org/packages/localheinz/test-util)
[![Total Downloads](https://poser.pugx.org/localheinz/test-util/downloads)](https://packagist.org/packages/localheinz/test-util)

As an alternative to [`refinery29/test-util`](https://github.com/refinery29/test-util), this repository provides a test helper.

## Installation

Run

```
$ composer require --dev localheinz/test-util
```

## Usage

Import the `Localheinz\Test\Util\Helper` trait into your test class:

```php
<?php

declare(strict_types=1);

namespace Foo\Bar\Test\Unit;

use Localheinz\Test\Util\Helper;
use PHPUnit\Framework\TestCase;

final class BazTest extends TestCase
{
    use Helper;
} 
```

### Easy access to localized instances of `Faker\Generator`

Assuming you have installed [`fzaninotto/faker`](http://github.com/fzaninotto/Faker), you can use

* `faker(string $locale = \Faker\Factory::DEFAULT_LOCALE) : \Faker\Generator`

to fetch an instance of `Faker\Generator`.

```php
<?php

declare(strict_types=1);

namespace Example\Test\Unit;

use Example\Player;
use Localheinz\Test\Util\Helper;
use PHPUnit\Framework\TestCase;

final class PlayerTest extends TestCase
{
    use Helper;
    
    public function testConstructorSetsValues()
    {
        $name = $this->faker()->firstName;
        
        $player = new Player($name);
        
        $this->assertSame($name, $player->firstName());
    }
} 
```

### Additional Assertions

In addition to the assertions made available by extending from `PHPUnit\Framework\TestCase`, 
the `Helper` trait provides the following assertions:

* `assertClassExists(string $className)`
* `assertClassImplementsInterface(string $interfaceName, string $className)`
* `assertClassIsAbstractOrFinal(string $className)`
* `assertInterfaceExists(string $className)`
* `assertTraitExists(string $className)`

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](.github/CODE_OF_CONDUCT.md).

## License

This package is licensed using the MIT License.
