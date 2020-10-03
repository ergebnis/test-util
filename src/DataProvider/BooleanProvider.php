<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2020 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/test-util
 */

namespace Ergebnis\Test\Util\DataProvider;

use Ergebnis\Test\Util;

final class BooleanProvider
{
    use Util\Helper;

    /**
     * @return \Generator<string, array{0: bool}>
     */
    public static function arbitrary(): \Generator
    {
        yield from self::provide(self::values());
    }

    /**
     * @return \Generator<string, array{0: bool}>
     */
    public static function false(): \Generator
    {
        yield from self::provideWhere(self::values(), static function (bool $value): bool {
            return false === $value;
        });
    }

    /**
     * @return \Generator<string, array{0: bool}>
     */
    public static function true(): \Generator
    {
        yield from self::provideWhere(self::values(), static function (bool $value): bool {
            return true === $value;
        });
    }

    /**
     * @return array<string, bool>
     */
    private static function values(): array
    {
        return [
            'boolean-false' => false,
            'boolean-true' => true,
        ];
    }
}
