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
        $tests = [
            'int-less-than-minus-one' => static function (int $value): bool {
                return -1 > $value;
            },
            'int-minus-one' => -1,
            'int-zero' => 0,
            'int-plus-one' => 1,
            'int-greater-than-plus-one' => static function (int $value): bool {
                return 1 < $value;
            },
        ];

        $provider = IntProvider::arbitrary();

        self::assertProvidesDataForValuesPassingTests($tests, $provider);
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
        $tests = [
            'int-less-than-minus-one' => static function (int $value): bool {
                return -1 > $value;
            },
            'int-minus-one' => -1,
        ];

        $provider = IntProvider::lessThanZero();

        self::assertProvidesDataForValuesPassingTests($tests, $provider);
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
        $tests = [
            'int-plus-one' => 1,
            'int-greater-than-plus-one' => static function (int $value): bool {
                return 1 < $value;
            },
        ];

        $provider = IntProvider::greaterThanZero();

        self::assertProvidesDataForValuesPassingTests($tests, $provider);
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
        $tests = [
            'int-less-than-minus-one' => static function (int $value): bool {
                return -1 > $value;
            },
            'int-minus-one' => -1,
            'int-zero' => 0,
        ];

        $provider = IntProvider::lessThanOne();

        self::assertProvidesDataForValuesPassingTests($tests, $provider);
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
        $tests = [
            'int-greater-than-plus-one' => static function (int $value): bool {
                return 1 < $value;
            },
        ];

        $provider = IntProvider::greaterThanOne();

        self::assertProvidesDataForValuesPassingTests($tests, $provider);
    }
}
