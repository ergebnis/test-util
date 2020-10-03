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
        $tests = [
            'string-arbitrary-sentence' => static function (string $value): bool {
                return '' !== $value && '' !== \trim($value);
            },
            'string-arbitrary-word' => static function (string $value): bool {
                return '' !== $value && '' !== \trim($value);
            },
            'string-untrimmed-carriage-return' => static function (string $value): bool {
                return 1 === \preg_match('/^\r{1,5}\w+\r{1,5}$/', $value);
            },
            'string-untrimmed-line-feed' => static function (string $value): bool {
                return 1 === \preg_match('/^\n{1,5}\w+\n{1,5}$/', $value);
            },
            'string-untrimmed-space' => static function (string $value): bool {
                return 1 === \preg_match('/^\s{1,5}\w+\s{1,5}$/', $value);
            },
            'string-untrimmed-tab' => static function (string $value): bool {
                return 1 === \preg_match('/^\t{1,5}\w+\t{1,5}$/', $value);
            },
        ];

        $provider = StringProvider::arbitrary();

        self::assertProvidesDataForValuesPassingTests($tests, $provider);
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
        $tests = [
            'string-untrimmed-carriage-return' => static function (string $value): bool {
                return 1 === \preg_match('/^\r{1,5}\w+\r{1,5}$/', $value);
            },
            'string-untrimmed-line-feed' => static function (string $value): bool {
                return 1 === \preg_match('/^\n{1,5}\w+\n{1,5}$/', $value);
            },
            'string-untrimmed-space' => static function (string $value): bool {
                return 1 === \preg_match('/^\s{1,5}\w+\s{1,5}$/', $value);
            },
            'string-untrimmed-tab' => static function (string $value): bool {
                return 1 === \preg_match('/^\t{1,5}\w+\t{1,5}$/', $value);
            },
        ];

        $provider = StringProvider::untrimmed();

        self::assertProvidesDataForValuesPassingTests($tests, $provider);
    }
}
