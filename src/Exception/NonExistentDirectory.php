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

namespace Ergebnis\Test\Util\Exception;

/**
 * @internal
 */
final class NonExistentDirectory extends \InvalidArgumentException
{
    public static function fromDirectory(string $directory): self
    {
        return new self(\sprintf(
            'Directory "%s" does not exist.',
            $directory
        ));
    }
}
