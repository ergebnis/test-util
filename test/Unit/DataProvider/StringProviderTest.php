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
use Ergebnis\Test\Util\Test\Util;

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
     * @param mixed $value
     */
    public function testArbitraryProvidesString($value): void
    {
        self::assertIsString($value);
    }

    public function testArbitraryReturnsGeneratorThatProvidesStringsThatAreNeitherEmptyNorBlank(): void
    {
        $specifications = [
            'string-arbitrary-sentence' => Util\DataProvider\Specification\Closure::create(static function (string $value): bool {
                return '' !== $value && '' !== \trim($value);
            }),
            'string-arbitrary-word' => Util\DataProvider\Specification\Closure::create(static function (string $value): bool {
                return '' !== $value && '' !== \trim($value);
            }),
            'string-blank-carriage-return' => Util\DataProvider\Specification\Identical::create("\r"),
            'string-blank-line-feed' => Util\DataProvider\Specification\Identical::create("\n"),
            'string-blank-space' => Util\DataProvider\Specification\Identical::create(' '),
            'string-blank-tab' => Util\DataProvider\Specification\Identical::create("\t"),
            'string-empty' => Util\DataProvider\Specification\Identical::create(''),
            'string-untrimmed-carriage-return' => Util\DataProvider\Specification\Pattern::create('/^\r{1,5}\w+\r{1,5}$/'),
            'string-untrimmed-line-feed' => Util\DataProvider\Specification\Pattern::create('/^\n{1,5}\w+\n{1,5}$/'),
            'string-untrimmed-space' => Util\DataProvider\Specification\Pattern::create('/^\s{1,5}\w+\s{1,5}$/'),
            'string-untrimmed-tab' => Util\DataProvider\Specification\Pattern::create('/^\t{1,5}\w+\t{1,5}$/'),
        ];

        $provider = StringProvider::arbitrary();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
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
        $specifications = [
            'string-blank-carriage-return' => Util\DataProvider\Specification\Identical::create("\r"),
            'string-blank-line-feed' => Util\DataProvider\Specification\Identical::create("\n"),
            'string-blank-space' => Util\DataProvider\Specification\Identical::create(' '),
            'string-blank-tab' => Util\DataProvider\Specification\Identical::create("\t"),
        ];

        $provider = StringProvider::blank();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
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
        $specifications = [
            'string-empty' => Util\DataProvider\Specification\Identical::create(''),
        ];

        $provider = StringProvider::empty();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
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

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\StringProvider::trimmed()
     *
     * @param mixed $value
     */
    public function testTrimmedProvidesString($value): void
    {
        self::assertIsString($value);
    }

    public function testTrimmedReturnsGeneratorThatProvidesStringsThatAreTrimmed(): void
    {
        $specifications = [
            'string-arbitrary-sentence' => Util\DataProvider\Specification\Closure::create(static function (string $value): bool {
                return '' !== $value && '' !== \trim($value);
            }),
            'string-arbitrary-word' => Util\DataProvider\Specification\Closure::create(static function (string $value): bool {
                return '' !== $value && '' !== \trim($value);
            }),
        ];

        $provider = StringProvider::trimmed();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
    }

    public function testUntrimmedReturnsGeneratorThatProvidesUntrimmedStrings(): void
    {
        $specifications = [
            'string-untrimmed-carriage-return' => Util\DataProvider\Specification\Pattern::create('/^\r{1,5}\w+\r{1,5}$/'),
            'string-untrimmed-line-feed' => Util\DataProvider\Specification\Pattern::create('/^\n{1,5}\w+\n{1,5}$/'),
            'string-untrimmed-space' => Util\DataProvider\Specification\Pattern::create('/^\s{1,5}\w+\s{1,5}$/'),
            'string-untrimmed-tab' => Util\DataProvider\Specification\Pattern::create('/^\t{1,5}\w+\t{1,5}$/'),
        ];

        $provider = StringProvider::untrimmed();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
    }
}
