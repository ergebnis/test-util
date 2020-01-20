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

namespace Ergebnis\Test\Util\Exception;

/**
 * @internal
 */
final class NonExistentExcludeClass extends \InvalidArgumentException
{
    public static function fromClassName(string $className): self
    {
        return new self(\sprintf(
            'Exclude class "%s" does not exist.',
            $className
        ));
    }
}
