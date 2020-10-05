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

namespace Ergebnis\Test\Util\Test\Util\DataProvider\Specification;

final class Equal implements Specification
{
    private $value;

    private function __construct($value)
    {
        $this->value = $value;
    }

    public static function create($value): self
    {
        return new self($value);
    }

    public function isSatisfiedBy($value): bool
    {
        return $this->value == $value;
    }
}
