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

namespace Ergebnis\Test\Util\Test\Unit\DataProvider;

use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Test\Util\DataProvider\BooleanProvider
 */
final class BooleanProviderTest extends Framework\TestCase
{
    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\BooleanProvider::arbitrary()
     *
     * @param mixed $value
     */
    public function testArbitraryProvidesBoolean($value): void
    {
        self::assertIsBool($value);
    }

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\BooleanProvider::false()
     *
     * @param mixed $value
     */
    public function testFalseProvidesFalse($value): void
    {
        self::assertFalse($value);
    }

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\BooleanProvider::true()
     *
     * @param mixed $value
     */
    public function testTrueProvidesTrue($value): void
    {
        self::assertTrue($value);
    }
}
