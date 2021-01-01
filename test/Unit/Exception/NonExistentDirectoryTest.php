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

use Ergebnis\Test\Util\Exception\NonExistentDirectory;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Test\Util\Exception\NonExistentDirectory
 *
 * @uses \Ergebnis\Test\Util\Helper
 */
final class NonExistentDirectoryTest extends Framework\TestCase
{
    use Helper;

    public function testExtendsInvalidArgumentException(): void
    {
        self::assertClassExtends(\InvalidArgumentException::class, NonExistentDirectory::class);
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
