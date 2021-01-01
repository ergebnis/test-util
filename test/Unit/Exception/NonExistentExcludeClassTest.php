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

namespace Ergebnis\Test\Util\Test\Unit\Exception;

use Ergebnis\Test\Util\Exception\NonExistentExcludeClass;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Test\Util\Exception\NonExistentExcludeClass
 *
 * @uses \Ergebnis\Test\Util\Helper
 */
final class NonExistentExcludeClassTest extends Framework\TestCase
{
    use Helper;

    public function testExtendsInvalidArgumentException(): void
    {
        self::assertClassExtends(\InvalidArgumentException::class, NonExistentExcludeClass::class);
    }

    public function testFromClassNameReturnsException(): void
    {
        $className = __NAMESPACE__ . '\\NonExistentClass';

        $exception = NonExistentExcludeClass::fromClassName($className);

        self::assertSame(0, $exception->getCode());

        $message = \sprintf(
            'Exclude class "%s" does not exist.',
            $className
        );

        self::assertSame($message, $exception->getMessage());
    }
}
