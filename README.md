# test-util

[![Integrate](https://github.com/ergebnis/test-util/workflows/Integrate/badge.svg?branch=main)](https://github.com/ergebnis/test-util/actions)
[![Prune](https://github.com/ergebnis/test-util/workflows/Prune/badge.svg?branch=main)](https://github.com/ergebnis/test-util/actions)
[![Release](https://github.com/ergebnis/test-util/workflows/Release/badge.svg?branch=main)](https://github.com/ergebnis/test-util/actions)
[![Renew](https://github.com/ergebnis/test-util/workflows/Renew/badge.svg?branch=main)](https://github.com/ergebnis/test-util/actions)

[![Code Coverage](https://codecov.io/gh/ergebnis/test-util/branch/main/graph/badge.svg)](https://codecov.io/gh/ergebnis/test-util)
[![Type Coverage](https://shepherd.dev/github/ergebnis/test-util/coverage.svg)](https://shepherd.dev/github/ergebnis/test-util)

[![Latest Stable Version](https://poser.pugx.org/ergebnis/test-util/v/stable)](https://packagist.org/packages/ergebnis/test-util)
[![Total Downloads](https://poser.pugx.org/ergebnis/test-util/downloads)](https://packagist.org/packages/ergebnis/test-util)

Provides a helper trait and generic data providers for tests.

## Installation

Run

```sh
$ composer require --dev ergebnis/test-util
```

## Usage

### `Helper`

Import the `Ergebnis\Test\Util\Helper` trait into your test class:

```php
<?php

declare(strict_types=1);

namespace Foo\Bar\Test\Unit;

use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

final class BazTest extends Framework\TestCase
{
    use Helper;
}
```

#### Easy access to localized instances of `Faker\Generator`

The `Helper` trait provides a method to fetch a localized instance of `Faker\Generator`:

* `faker(string $locale = 'en_US') : \Faker\Generator`

```php
<?php

declare(strict_types=1);

namespace Example\Test\Unit;

use Ergebnis\Test\Util\Helper;
use Example\Player;
use PHPUnit\Framework;

final class PlayerTest extends Framework\TestCase
{
    use Helper;

    public function testConstructorSetsValues(): void
    {
        $name = self::faker()->firstName;

        $player = new Player($name);

        $this->assertSame($name, $player->firstName());
    }
}
```

For reference, see [`fzaninotto/faker`](https://github.com/fzaninotto/Faker).

#### Additional Assertions

In addition to the assertions made available by extending from `PHPUnit\Framework\TestCase`, the `Helper` trait provides the following assertions:

* `assertClassesAreAbstractOrFinal(string $directory, array $excludeClassNames = [])`
* `assertClassesHaveTests(string $directory, string $namespace, string $testNamespace, array $excludeClassyNames = [])`
* `assertClassExists(string $className)`
* `assertClassExtends(string $parentClassName, string $className)`
* `assertClassImplementsInterface(string $interfaceName, string $className)`
* `assertClassIsAbstract(string $className)`
* `assertClassIsFinal(string $className)`
* `assertClassSatisfiesSpecification(callable $specification, string $className, string $message = '')`
* `assertClassUsesTrait(string $traitName, string $className)`
* `assertClassyConstructsSatisfySpecification(callable $specification, string $directory, array $excludeClassNames = [], $message = '')`
* `assertInterfaceExists(string $interfaceName)`
* `assertInterfaceExtends(string $parentInterfaceName, string $interfaceName)`
* `assertInterfaceSatisfiesSpecification(callable $specification, string $interfaceName, string $message = '')`
* `assertTraitExists(string $traitName)`
* `assertTraitSatisfiesSpecification(callable $specification, string $traitName, string $message = '')`

### Data Providers

This package provides the following generic data providers:

* [`Ergebnis\Test\Util\DataProvider\BoolProvider`](https://github.com/ergebnis/test-util#dataproviderboolprovider)
* [`Ergebnis\Test\Util\DataProvider\NullProvider`](https://github.com/ergebnis/test-util#dataprovidernullprovider)
* [`Ergebnis\Test\Util\DataProvider\StringProvider`](https://github.com/ergebnis/test-util#dataproviderstringprovider)

Since it is possible to use multiple `@dataProvider` annotations for test methods, these generic data providers allow for reuse and composition of data providers:

```php
<?php

declare(strict_types=1);

namespace Example\Test;

use PHPUnit\Framework;

final class ExampleTest extends Framework\TestCase
{
    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\StringProvider::blank()
     * @dataProvider \Ergebnis\Test\Util\DataProvider\StringProvider::empty()
     *
     * @param string $value
     */
    public function testFromNameRejectsBlankOrEmptyStrings(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value can not be an empty or blank string.');

        UserName::fromString($value);
    }
}
```

#### `DataProvider\BoolProvider`

* `arbitrary()` provides `true`, `false`
* `false()` provides `false`
* `true()` provides `true`

For examples, see [`Ergebnis\Test\Util\Test\Unit\DataProvider\BoolProviderTest`](test/Unit/DataProvider/BoolProviderTest.php).

#### `DataProvider\IntProvider`

* `arbitrary()` provides arbitrary `int`s
* `greaterThanOne()` provides `int`s greater than `1`
* `greaterThanZero()` provides `int`s greater than `0`
* `lessThanOne()` provides `int`s less than `1`
* `lessThanZero()` provides `int`s less than `0`
* `one()` provides `1`
* `zero()` provides `0`

For examples, see [`Ergebnis\Test\Util\Test\Unit\DataProvider\IntProviderTest`](test/Unit/DataProvider/IntProviderTest.php).

#### `DataProvider\NullProvider`

* `null()` provides `null`

For examples, see [`Ergebnis\Test\Util\Test\Unit\DataProvider\NullProviderTest`](test/Unit/DataProvider/NullProviderTest.php).

#### `DataProvider\StringProvider`

* `arbitrary()` provides arbitrary `string`s
* `blank()` provides `string`s consisting of whitespace characters only
* `empty()` provides an empty `string`
* `untrimmed()` provides `string`s with leading and trailing whitespace

For examples, see [`Ergebnis\Test\Util\Test\Unit\DataProvider\StringProviderTest`](test/Unit/DataProvider/StringProviderTest.php).

## Changelog

Please have a look at [`CHANGELOG.md`](CHANGELOG.md).

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](https://github.com/ergebnis/.github/blob/main/CODE_OF_CONDUCT.md).

## License

This package is licensed using the MIT License.

Please have a look at [`LICENSE.md`](LICENSE.md).

## Credits

The [`SrcCodeTest`](test/AutoReview/SrcCodeTest.php) in this and other projects I maintain or contribute to is inspired by [`ProjectCodeTest`](https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/v2.0.0/tests/ProjectCodeTest.php) in [`friends-of-php/php-cs-fixer`](https://github.com/FriendsOfPHP/PHP-CS-Fixer), and was initially created by [Dariusz Rumiński](https://github.com/keradus).

## Curious what I am building?

:mailbox_with_mail: [Subscribe to my list](https://localheinz.com/projects/), and I will occasionally send you an email to let you know what I am working on.
