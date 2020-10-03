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
 * @covers \Ergebnis\Test\Util\DataProvider\StringProvider
 */
final class StringProviderTest extends Framework\TestCase
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

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\StringProvider::empty()
     *
     * @param string $value
     */
    public function testEmptyProvidesEmptyString(string $value): void
    {
        self::assertSame('', $value);
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
}
