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

namespace Ergebnis\Test\Util\DataProvider;

use Ergebnis\Test\Util;

final class NullProvider
{
    use Util\Helper;

    /**
     * @return \Generator<string, array{0: null}>
     */
    public static function null(): \Generator
    {
        yield from self::provideDataForValues([
            'null' => null,
        ]);
    }
}
