<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @link https://github.com/localheinz/test-util
 */

namespace Localheinz\Test\Util\Constraint;

use PHPUnit\Framework;

final class TraitExists extends Framework\Constraint\Constraint
{
    public function toString(): string
    {
        return 'trait exists';
    }

    protected function matches($other): bool
    {
        return \trait_exists($other);
    }

    protected function failureDescription($other): string
    {
        return \sprintf(
            'a trait "%s" exists',
            $other
        );
    }
}
