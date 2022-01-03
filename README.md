# test-util

[![Integrate](https://github.com/ergebnis/test-util/workflows/Integrate/badge.svg)](https://github.com/ergebnis/test-util/actions)
[![Prune](https://github.com/ergebnis/test-util/workflows/Prune/badge.svg)](https://github.com/ergebnis/test-util/actions)
[![Release](https://github.com/ergebnis/test-util/workflows/Release/badge.svg)](https://github.com/ergebnis/test-util/actions)
[![Renew](https://github.com/ergebnis/test-util/workflows/Renew/badge.svg)](https://github.com/ergebnis/test-util/actions)

[![Code Coverage](https://codecov.io/gh/ergebnis/test-util/branch/main/graph/badge.svg)](https://codecov.io/gh/ergebnis/test-util)
[![Type Coverage](https://shepherd.dev/github/ergebnis/test-util/coverage.svg)](https://shepherd.dev/github/ergebnis/test-util)

[![Latest Stable Version](https://poser.pugx.org/ergebnis/test-util/v/stable)](https://packagist.org/packages/ergebnis/test-util)
[![Total Downloads](https://poser.pugx.org/ergebnis/test-util/downloads)](https://packagist.org/packages/ergebnis/test-util)

Provides a helper trait for tests.

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

use Ergebnis\Test\Util;
use PHPUnit\Framework;

final class BazTest extends Framework\TestCase
{
    use Util\Helper;
}
```

#### Easy access to localized instances of `Faker\Generator`

The `Helper` trait provides a method to fetch a localized instance of `Faker\Generator`:

* `faker(string $locale = 'en_US') : \Faker\Generator`

```php
<?php

declare(strict_types=1);

namespace Example\Test\Unit;

use Ergebnis\Test\Util;
use Example\Player;
use PHPUnit\Framework;

final class PlayerTest extends Framework\TestCase
{
    use Util\Helper;

    public function testConstructorSetsValues(): void
    {
        $name = self::faker()->firstName;

        $player = new Player($name);

        $this->assertSame($name, $player->firstName());
    }
}
```

For reference, see [`fzaninotto/faker`](https://github.com/fzaninotto/Faker).

## Changelog

Please have a look at [`CHANGELOG.md`](CHANGELOG.md).

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](https://github.com/ergebnis/.github/blob/main/CODE_OF_CONDUCT.md).

## License

This package is licensed using the MIT License.

Please have a look at [`LICENSE.md`](LICENSE.md).

## Curious what I am building?

:mailbox_with_mail: [Subscribe to my list](https://localheinz.com/projects/), and I will occasionally send you an email to let you know what I am working on.
