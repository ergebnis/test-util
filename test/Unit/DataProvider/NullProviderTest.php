<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/test-util
 */

namespace Ergebnis\Test\Util\Test\Unit\DataProvider;

use Ergebnis\Test\Util\DataProvider\NullProvider;
use Ergebnis\Test\Util\Test\Util;

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
        $specifications = [
            'null' => Util\DataProvider\Specification\Identical::create(null),
        ];

        $provider = NullProvider::null();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
    }
}
