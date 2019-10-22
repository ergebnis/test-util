<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/localheinz/test-util
 */

namespace Localheinz\Test\Util\Test\Unit\Exception;

use Localheinz\Test\Util\Exception\NonExistentExcludeClass;
use Localheinz\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 * @coversNothing
 */
final class NonExistentExcludeClassTest extends Framework\TestCase
{
    use Helper;

    public function testExtendsInvalidArgumentException(): void
    {
        $this->assertClassExtends(\InvalidArgumentException::class, NonExistentExcludeClass::class);
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
