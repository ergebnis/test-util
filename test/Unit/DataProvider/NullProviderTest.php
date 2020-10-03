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

use Ergebnis\Test\Util\DataProvider\NullProvider;

/**
 * @internal
 *
 * @covers \Ergebnis\Test\Util\DataProvider\NullProvider
 */
final class NullProviderTest extends AbstractProviderTestCase
{
    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\NullProvider::null()
     *
     * @param mixed $value
     */
    public function testNullProvidesNull($value): void
    {
        self::assertNull($value);
    }

    public function testNullReturnsGeneratorThatProvidesNull(): void
    {
        $values = [
            'null' => null,
        ];

        $provider = NullProvider::null();

        self::assertProvidesDataForValues($values, $provider);
    }
}
