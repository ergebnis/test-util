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

use Ergebnis\Test\Util\DataProvider\ResourceProvider;
use Ergebnis\Test\Util\Test\Util;

/**
 * @internal
 *
 * @covers \Ergebnis\Test\Util\DataProvider\ResourceProvider
 */
final class ResourceProviderTest extends AbstractProviderTestCase
{
    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\ResourceProvider::resource()
     *
     * @param mixed $value
     */
    public function testResourceProvidesResource($value): void
    {
        self::assertIsResource($value);
    }

    public function testResourceReturnsGeneratorThatProvidesResource(): void
    {
        $specifications = [
            'resource' => Util\DataProvider\Specification\Closure::create(static function ($value): bool {
                return \is_resource($value);
            }),
        ];

        $provider = ResourceProvider::resource();

        self::assertProvidesDataSetsForValuesSatisfyingSpecifications($specifications, $provider);
    }
}
