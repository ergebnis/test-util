<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/test-util
 */

namespace Ergebnis\Test\Util\DataProvider;

use Ergebnis\Test\Util;

/**
 * @deprecated use ergebnis/data-provider instead
 * @see https://github.com/ergebnis/data-provider
 */
final class ResourceProvider
{
    use Util\Helper;

    /**
     * @return \Generator<string, array{0: resource}>
     */
    public static function resource(): \Generator
    {
        yield from self::provideDataForValues([
            'resource' => \fopen(__FILE__, 'rb'),
        ]);
    }
}
