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

use Ergebnis\Test\Util\DataProvider\StringProvider;

/**
 * @internal
 *
 * @covers \Ergebnis\Test\Util\DataProvider\StringProvider
 */
final class StringProviderTest extends AbstractProviderTestCase
{
    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\StringProvider::arbitrary()
     *
     * @param string $value
     */
    public function testArbitraryProvidesString(string $value): void
    {
        self::assertNotSame('', \trim($value));
    }

    public function testArbitraryReturnsGeneratorThatProvidesStringsThatAreNeitherEmptyNorBlank(): void
    {
        $test = static function (string $value): bool {
            return '' === \trim($value);
        };

        $provider = StringProvider::arbitrary();

        self::assertProvidesDataForValuesWhereNot($test, $provider);
    }

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\StringProvider::blank()
     *
     * @param string $value
     */
    public function testBlankProvidesBlankString(string $value): void
    {
        self::assertSame('', \trim($value));
        self::assertNotSame('', $value);
    }

    public function testBlankReturnsGeneratorThatProvidesStringsThatAreNeitherEmptyNorBlank(): void
    {
        $values = [
            'string-blank-carriage-return' => "\r",
            'string-blank-line-feed' => "\n",
            'string-blank-space' => ' ',
            'string-blank-tab' => "\t",
        ];

        $provider = StringProvider::blank();

        self::assertProvidesDataForValues($values, $provider);
    }

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\StringProvider::empty()
     *
     * @param string $value
     */
    public function testEmptyProvidesEmptyString(string $value): void
    {
        self::assertSame('', $value);
    }

    public function testEmptyReturnsGeneratorThatProvidesAnEmptyString(): void
    {
        $values = [
            'string-empty' => '',
        ];

        $provider = StringProvider::empty();

        self::assertProvidesDataForValues($values, $provider);
    }

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\StringProvider::untrimmed()
     *
     * @param string $value
     */
    public function testUntrimmedProvidesUntrimmedString(string $value): void
    {
        self::assertNotSame(\trim($value), $value);
        self::assertNotSame('', $value);
        self::assertNotSame('', \trim($value));
    }

    public function testUntrimmedReturnsGeneratorThatProvidesUntrimmedStrings(): void
    {
        $test = static function (string $value): bool {
            return \trim($value) !== $value
                && '' !== \trim($value);
        };

        $provider = StringProvider::untrimmed();

        self::assertProvidesDataForValuesWhere($test, $provider);
    }
}
