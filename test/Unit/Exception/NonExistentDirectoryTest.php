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

use Localheinz\Test\Util\Exception\NonExistentDirectory;
use Localheinz\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Localheinz\Test\Util\Exception\NonExistentDirectory
 *
 * @uses \Localheinz\Test\Util\Helper
 */
final class NonExistentDirectoryTest extends Framework\TestCase
{
    use Helper;

    public function testExtendsInvalidArgumentException(): void
    {
        $this->assertClassExtends(\InvalidArgumentException::class, NonExistentDirectory::class);
    }

    public function testFromDirectoryReturnsException(): void
    {
        $directory = __DIR__ . '/non-existent-directory';

        $exception = NonExistentDirectory::fromDirectory($directory);

        self::assertSame(0, $exception->getCode());

        $message = \sprintf(
            'Directory "%s" does not exist.',
            $directory
        );

        self::assertSame($message, $exception->getMessage());
    }
}
