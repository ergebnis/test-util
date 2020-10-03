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

use Ergebnis\Test\Util\DataProvider\BooleanProvider;

/**
 * @internal
 *
 * @covers \Ergebnis\Test\Util\DataProvider\BooleanProvider
 */
final class BooleanProviderTest extends AbstractProviderTestCase
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

    public function testArbitraryReturnsGeneratorThatProvidesBooleanValues(): void
    {
        $values = [
            'boolean-false' => false,
            'boolean-true' => true,
        ];

        $provider = BooleanProvider::arbitrary();

        self::assertProvidesDataForValues($values, $provider);
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

    public function testFalseReturnsGeneratorThatProvidesFalse(): void
    {
        $values = [
            'boolean-false' => false,
        ];

        $provider = BooleanProvider::false();

        self::assertProvidesDataForValues($values, $provider);
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

    public function testTrueReturnsGeneratorThatProvidesTrue(): void
    {
        $values = [
            'boolean-true' => true,
        ];

        $provider = BooleanProvider::true();

        self::assertProvidesDataForValues($values, $provider);
    }
}
