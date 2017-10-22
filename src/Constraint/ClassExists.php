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

final class ClassExists extends Framework\Constraint\Constraint
{
    public function toString(): string
    {
        return 'class exists';
    }

    protected function matches($other): bool
    {
        return \class_exists($other);
    }

    protected function failureDescription($other): string
    {
        return \sprintf(
            'a class "%s" exists',
            $other
        );
    }
}
