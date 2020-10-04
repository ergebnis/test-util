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
use Ergebnis\Test\Util\Test\Util;

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
        $specifications = [
            'int-less-than-minus-one' => Util\DataProvider\Specification\Closure::create(static function (int $value): bool {
                return -1 > $value;
            }),
            'int-minus-one' => Util\DataProvider\Specification\Identical::create(-1),
            'int-zero' => Util\DataProvider\Specification\Identical::create(0),
            'int-plus-one' => Util\DataProvider\Specification\Identical::create(1),
            'int-greater-than-plus-one' => Util\DataProvider\Specification\Closure::create(static function (int $value): bool {
                return  1 < $value;
            }),
        ];

        $provider = IntProvider::arbitrary();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
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
        $specifications = [
            'int-less-than-minus-one' => Util\DataProvider\Specification\Closure::create(static function (int $value): bool {
                return -1 > $value;
            }),
            'int-minus-one' => Util\DataProvider\Specification\Identical::create(-1),
        ];

        $provider = IntProvider::lessThanZero();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
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
        $specifications = [
            'int-zero' => Util\DataProvider\Specification\Identical::create(0),
        ];

        $provider = IntProvider::zero();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
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
        $specifications = [
            'int-plus-one' => Util\DataProvider\Specification\Identical::create(1),
            'int-greater-than-plus-one' => Util\DataProvider\Specification\Closure::create(static function (int $value): bool {
                return  1 < $value;
            }),
        ];

        $provider = IntProvider::greaterThanZero();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
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
        $specifications = [
            'int-less-than-minus-one' => Util\DataProvider\Specification\Closure::create(static function (int $value): bool {
                return -1 > $value;
            }),
            'int-minus-one' => Util\DataProvider\Specification\Identical::create(-1),
            'int-zero' => Util\DataProvider\Specification\Identical::create(0),
        ];

        $provider = IntProvider::lessThanOne();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
    }

    public function testOneReturnsGeneratorThatProvidesOne(): void
    {
        $specifications = [
            'int-plus-one' => Util\DataProvider\Specification\Identical::create(1),
        ];

        $provider = IntProvider::one();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
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
        $specifications = [
            'int-greater-than-plus-one' => Util\DataProvider\Specification\Closure::create(static function (int $value): bool {
                return  1 < $value;
            }),
        ];

        $provider = IntProvider::greaterThanOne();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
    }
}
