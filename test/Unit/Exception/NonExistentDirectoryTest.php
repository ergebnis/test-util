<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/localheinz/test-util
 */

namespace Localheinz\Test\Util\Test\Unit\Exception;

use Localheinz\Test\Util\Exception\NonExistentDirectory;
use Localheinz\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 */
final class NonExistentDirectoryTest extends Framework\TestCase
{
    use Helper;

    public function testExtendsInvalidArgumentException()
    {
        $this->assertClassExtends(\InvalidArgumentException::class, NonExistentDirectory::class);
    }

    public function testFromDirectoryReturnsException()
    {
        $directory = \implode(
            '/',
            $this->faker()->words
        );

        $exception = NonExistentDirectory::fromDirectory($directory);

        $this->assertInstanceOf(NonExistentDirectory::class, $exception);
        $this->assertSame(0, $exception->getCode());

        $message = \sprintf(
            'Directory "%s" does not exist.',
            $directory
        );

        $this->assertSame($message, $exception->getMessage());
    }
}
