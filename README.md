# test-util

[![Build Status](https://travis-ci.org/localheinz/test-util.svg?branch=master)](https://travis-ci.org/localheinz/test-util)
[![codecov](https://codecov.io/gh/localheinz/test-util/branch/master/graph/badge.svg)](https://codecov.io/gh/localheinz/test-util)
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

The `Helper` trait provides a method to fetch a localized instance of `Faker\Generator`:

* `faker(string $locale = 'en_US') : \Faker\Generator`

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

For reference, see [`fzaninotto/faker`](https://github.com/fzaninotto/Faker).

### Additional Assertions

In addition to the assertions made available by extending from `PHPUnit\Framework\TestCase`, 
the `Helper` trait provides the following assertions:

* `assertClassesAreAbstractOrFinal(string $directory, array $excludeClassNames = [])`
* `assertClassesHaveTests(string $directory, string $namespace, string $testNamespace, array $excludeClassyNames = [])`
* `assertClassExists(string $className)`
* `assertClassExtends(string $parentClassName, string $className)`
* `assertClassImplementsInterface(string $interfaceName, string $className)`
* `assertClassIsAbstract(string $className)`
* `assertClassIsAbstractOrFinal(string $className)`
* `assertClassIsFinal(string $className)`
* `assertClassSatisfiesSpecification(callable $specification, string $className, string $message = '')`
* `assertClassUsesTrait(string $traitName, string $className)`
* `assertClassyConstructsSatisfySpecification(callable $specification, string $directory, array $excludeClassNames = [], $message = '')`
* `assertInterfaceExists(string $interfaceName)`
* `assertInterfaceExtends(string $parentInterfaceName, string $interfaceName)`
* `assertInterfaceSatisfiesSpecification(callable $specification, string $interfaceName, string $message = '')`
* `assertTraitExists(string $traitName)`
* `assertTraitSatisfiesSpecification(callable $specification, string $traitName, string $message = '')`

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](.github/CODE_OF_CONDUCT.md).

## License

This package is licensed using the MIT License.
