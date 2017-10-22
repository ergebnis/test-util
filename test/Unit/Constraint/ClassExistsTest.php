<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @link https://github.com/localheinz/test-util
 */

namespace Localheinz\Test\Util\Test\Unit\Constraint;

use Localheinz\Test\Util\Constraint\ClassExists;
use Localheinz\Test\Util\Test\Fixture;
use PHPUnit\Framework;

final class ClassExistsTest extends AbstractTestCase
{
    public function testCountReturnsOne()
    {
        $constraint = new ClassExists();

        $this->assertCount(1, $constraint);
    }

    public function testToStringReturnsDescription()
    {
        $constraint = new ClassExists();

        $this->assertSame('class exists', $constraint->toString());
    }

    /**
     * @dataProvider \Localheinz\Test\Util\Test\Unit\DataProvider::providerNotClass()
     *
     * @param string $other
     */
    public function testEvaluateReturnsFalseIfClassDoesNotExist(string $other)
    {
        $constraint = new ClassExists();

        $this->assertFalse($constraint->evaluate($other, '', true));
    }

    public function testEvaluateReturnsTrueIfClassExists()
    {
        $other = Fixture\ClassExists\ExampleClass::class;

        $constraint = new ClassExists();

        $this->assertTrue($constraint->evaluate($other, '', true));
    }

    /**
     * @dataProvider \Localheinz\Test\Util\Test\Unit\DataProvider::providerNotClass()
     *
     * @param string $other
     */
    public function testEvaluateThrowsAssertionFailedErrorWithDefaultMessageIfClassDoesNotExist(string $other)
    {
        $constraint = new ClassExists();

        try {
            $constraint->evaluate($other);
        } catch (Framework\AssertionFailedError $exception) {
            $expectedMessage = <<<TXT
Failed asserting that a class "$other" exists.
TXT;

            $this->assertSame($expectedMessage, $exception->getMessage());

            return;
        }

        $this->fail();
    }

    /**
     * @dataProvider \Localheinz\Test\Util\Test\Unit\DataProvider::providerNotClass()
     *
     * @param string $other
     */
    public function testEvaluateThrowsAssertionFailedErrorWithCustomMessageIfClassDoesNotExist(string $other)
    {
        $customMessage = $this->faker()->sentence();

        $constraint = new ClassExists();

        try {
            $constraint->evaluate($other, $customMessage);
        } catch (Framework\AssertionFailedError $exception) {
            $expectedMessage = <<<TXT
$customMessage
Failed asserting that a class "$other" exists.
TXT;

            $this->assertSame($expectedMessage, $exception->getMessage());

            return;
        }

        $this->fail();
    }

    public function testEvaluateReturnsNullIfClassExists()
    {
        $other = Fixture\ClassExists\ExampleClass::class;

        $constraint = new ClassExists();

        $this->assertNull($constraint->evaluate($other));
    }
}
