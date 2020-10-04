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

use Ergebnis\Test\Util\DataProvider\BoolProvider;
use Ergebnis\Test\Util\Test\Util;

/**
 * @internal
 *
 * @covers \Ergebnis\Test\Util\DataProvider\BoolProvider
 */
final class BoolProviderTest extends AbstractProviderTestCase
{
    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\BoolProvider::arbitrary()
     *
     * @param mixed $value
     */
    public function testArbitraryProvidesBool($value): void
    {
        self::assertIsBool($value);
    }

    public function testArbitraryReturnsGeneratorThatProvidesBoolValues(): void
    {
        $specifications = [
            'bool-false' => Util\DataProvider\Specification\Identical::create(false),
            'bool-true' => Util\DataProvider\Specification\Identical::create(true),
        ];

        $provider = BoolProvider::arbitrary();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
    }

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\BoolProvider::false()
     *
     * @param mixed $value
     */
    public function testFalseProvidesFalse($value): void
    {
        self::assertFalse($value);
    }

    public function testFalseReturnsGeneratorThatProvidesFalse(): void
    {
        $specifications = [
            'bool-false' => Util\DataProvider\Specification\Identical::create(false),
        ];

        $provider = BoolProvider::false();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
    }

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\BoolProvider::true()
     *
     * @param mixed $value
     */
    public function testTrueProvidesTrue($value): void
    {
        self::assertTrue($value);
    }

    public function testTrueReturnsGeneratorThatProvidesTrue(): void
    {
        $specifications = [
            'bool-true' => Util\DataProvider\Specification\Identical::create(true),
        ];

        $provider = BoolProvider::true();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
    }
}
