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

use Ergebnis\Test\Util\DataProvider\IntProvider;

/**
 * @internal
 *
 * @covers \Ergebnis\Test\Util\DataProvider\IntProvider
 */
final class IntProviderTest extends AbstractProviderTestCase
{
    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\IntProvider::arbitrary()
     *
     * @param mixed $value
     */
    public function testArbitraryProvidesInt($value): void
    {
        self::assertIsInt($value);
    }

    public function testArbitraryReturnsGeneratorThatProvidesIntValues(): void
    {
        $test = static function ($value): bool {
            return \is_int($value);
        };

        $provider = IntProvider::arbitrary();

        self::assertProvidesDataForValuesWhere($test, $provider);
    }

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\IntProvider::lessThanZero()
     *
     * @param int $value
     */
    public function testLessThanZeroProvidesIntLessThanZero(int $value): void
    {
        self::assertLessThan(0, $value);
    }

    public function testLessThanZeroReturnsGeneratorThatProvidesIntLessThanZero(): void
    {
        $test = static function (int $value): bool {
            return 0 > $value;
        };

        $provider = IntProvider::lessThanZero();

        self::assertProvidesDataForValuesWhere($test, $provider);
    }

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\IntProvider::zero()
     *
     * @param int $value
     */
    public function testZeroProvidesZero(int $value): void
    {
        self::assertSame(0, $value);
    }

    public function testZeroReturnsGeneratorThatProvidesZero(): void
    {
        $values = [
            'int-zero' => 0,
        ];

        $provider = IntProvider::zero();

        self::assertProvidesDataForValues($values, $provider);
    }

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\IntProvider::greaterThanZero()
     *
     * @param int $value
     */
    public function testGreaterThanZeroProvidesIntGreaterThanZero(int $value): void
    {
        self::assertGreaterThan(0, $value);
    }

    public function testGreaterThanZeroReturnsGeneratorThatProvidesIntGreaterThanZero(): void
    {
        $test = static function (int $value): bool {
            return 0 < $value;
        };

        $provider = IntProvider::greaterThanZero();

        self::assertProvidesDataForValuesWhere($test, $provider);
    }

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\IntProvider::lessThanOne()
     *
     * @param int $value
     */
    public function testLessThanOneProvidesIntLessThanOne(int $value): void
    {
        self::assertLessThan(1, $value);
    }

    public function testLessThanOneReturnsGeneratorThatProvidesIntLessThanOne(): void
    {
        $test = static function (int $value): bool {
            return 1 > $value;
        };

        $provider = IntProvider::lessThanOne();

        self::assertProvidesDataForValuesWhere($test, $provider);
    }

    public function testOneReturnsGeneratorThatProvidesOne(): void
    {
        $values = [
            'int-plus-one' => 1,
        ];

        $provider = IntProvider::one();

        self::assertProvidesDataForValues($values, $provider);
    }

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\IntProvider::greaterThanOne()
     *
     * @param int $value
     */
    public function testGreaterThanOneProvidesIntGreaterThanOne(int $value): void
    {
        self::assertGreaterThan(1, $value);
    }

    public function testGreaterThanOneReturnsGeneratorThatProvidesIntGreaterThanOne(): void
    {
        $test = static function (int $value): bool {
            return 1 < $value;
        };

        $provider = IntProvider::greaterThanOne();

        self::assertProvidesDataForValuesWhere($test, $provider);
    }
}
