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

namespace Ergebnis\Test\Util\Test\Util\DataProvider\Specification;

final class Closure implements Specification
{
    /**
     * @var \Closure
     */
    private $closure;

    private function __construct(\Closure $closure)
    {
        $this->closure = static function ($value) use ($closure): bool {
            return true === $closure($value);
        };
    }

    public static function create(\Closure $closure): self
    {
        return new self($closure);
    }

    public function isSatisfiedBy($value): bool
    {
        $closure = $this->closure;

        return $closure($value);
    }
}
