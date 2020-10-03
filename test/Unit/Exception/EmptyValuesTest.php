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

namespace Ergebnis\Test\Util\Test\Unit\Exception;

use Ergebnis\Test\Util\Exception\EmptyValues;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Test\Util\Exception\EmptyValues
 *
 * @uses \Ergebnis\Test\Util\Helper
 */
final class EmptyValuesTest extends Framework\TestCase
{
    use Helper;

    public function testExtendsInvalidArgumentException(): void
    {
        self::assertClassExtends(\InvalidArgumentException::class, EmptyValues::class);
    }

    public function testCreateReturnsException(): void
    {
        $exception = EmptyValues::create();

        self::assertSame('Values can not be empty.', $exception->getMessage());
    }

    public function testFilteredReturnsException(): void
    {
        $exception = EmptyValues::filtered();

        self::assertSame('Filtered values can not be empty.', $exception->getMessage());
    }
}
