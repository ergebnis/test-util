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

use Localheinz\Test\Util\Exception\InvalidExcludeClassName;
use Localheinz\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 */
final class InvalidExcludeClassNameTest extends Framework\TestCase
{
    use Helper;

    public function testExtendsInvalidArgumentException(): void
    {
        $this->assertClassExtends(\InvalidArgumentException::class, InvalidExcludeClassName::class);
    }

    /**
     * @dataProvider providerInvalidClassName
     *
     * @param mixed $className
     */
    public function testFromClassNameReturnsException($className): void
    {
        $exception = InvalidExcludeClassName::fromClassName($className);

        $this->assertInstanceOf(InvalidExcludeClassName::class, $exception);
        $this->assertSame(0, $exception->getCode());

        $message = \sprintf(
            'Exclude class name should be a string, got "%s" instead.',
            \is_object($className) ? \get_class($className) : \gettype($className)
        );

        $this->assertSame($message, $exception->getMessage());
    }

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
