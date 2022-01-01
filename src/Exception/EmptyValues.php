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

namespace Ergebnis\Test\Util\Exception;

final class EmptyValues extends \InvalidArgumentException
{
    public static function create(): self
    {
        return new self('Values can not be empty.');
    }

    public static function filtered(): self
    {
        return new self('Filtered values can not be empty.');
    }
}
