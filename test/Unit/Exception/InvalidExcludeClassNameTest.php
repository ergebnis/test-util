<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/test-util
 */

namespace Ergebnis\Test\Util\Test\Unit\Exception;

use Ergebnis\Test\Util\Exception\InvalidExcludeClassName;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Test\Util\Exception\InvalidExcludeClassName
 *
 * @uses \Ergebnis\Test\Util\Helper
 */
final class InvalidExcludeClassNameTest extends Framework\TestCase
{
    use Helper;

    public function testExtendsInvalidArgumentException(): void
    {
        self::assertClassExtends(\InvalidArgumentException::class, InvalidExcludeClassName::class);
    }

    /**
     * @dataProvider providerInvalidClassName
     *
     * @param mixed $className
     */
    public function testFromClassNameReturnsException($className): void
    {
        $exception = InvalidExcludeClassName::fromClassName($className);

        self::assertSame(0, $exception->getCode());

        $message = \sprintf(
            'Exclude class name should be a string, got "%s" instead.',
            \is_object($className) ? \get_class($className) : \gettype($className)
        );

        self::assertSame($message, $exception->getMessage());
    }

    /**
     * @return \Generator<array<null|array|bool|float|int|resource|\stdClass>>
     */
    public function providerInvalidClassName(): \Generator
    {
        $className = [
            'array' => [
                'foo',
                'bar',
                'baz',
            ],
            'boolean-false' => false,
            'boolean-true' => true,
            'float' => 3.14,
            'integer' => 9000,
            'null' => null,
            'object' => new \stdClass(),
            'resource' => \fopen(__FILE__, 'rb'),
        ];

        foreach ($className as $key => $classyName) {
            yield $key => [
                $classyName,
            ];
        }
    }
}
