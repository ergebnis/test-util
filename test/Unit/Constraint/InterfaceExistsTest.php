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

use Localheinz\Test\Util\Constraint\InterfaceExists;
use Localheinz\Test\Util\Test\Fixture;
use PHPUnit\Framework;

final class InterfaceExistsTest extends AbstractTestCase
{
    public function testCountReturnsOne()
    {
        $constraint = new InterfaceExists();

        $this->assertCount(1, $constraint);
    }

    public function testToStringReturnsDescription()
    {
        $constraint = new InterfaceExists();

        $this->assertSame('interface exists', $constraint->toString());
    }

    /**
     * @dataProvider \Localheinz\Test\Util\Test\Unit\DataProvider::providerNotInterface()
     *
     * @param string $other
     */
    public function testEvaluateReturnsFalseIfInterfaceDoesNotExist(string $other)
    {
        $constraint = new InterfaceExists();

        $this->assertFalse($constraint->evaluate($other, '', true));
    }

    public function testEvaluateReturnsTrueIfInterfaceExists()
    {
        $other = Fixture\InterfaceExists\ExampleInterface::class;

        $constraint = new InterfaceExists();

        $this->assertTrue($constraint->evaluate($other, '', true));
    }

    /**
     * @dataProvider \Localheinz\Test\Util\Test\Unit\DataProvider::providerNotInterface()
     *
     * @param string $other
     */
    public function testEvaluateThrowsAssertionFailedErrorWithDefaultMessageIfInterfaceDoesNotExist(string $other)
    {
        $constraint = new InterfaceExists();

        try {
            $constraint->evaluate($other);
        } catch (Framework\AssertionFailedError $exception) {
            $expectedMessage = <<<TXT
Failed asserting that an interface "$other" exists.
TXT;

            $this->assertSame($expectedMessage, $exception->getMessage());

            return;
        }

        $this->fail();
    }

    /**
     * @dataProvider \Localheinz\Test\Util\Test\Unit\DataProvider::providerNotInterface()
     *
     * @param string $other
     */
    public function testEvaluateThrowsAssertionFailedErrorWithCustomMessageIfInterfaceDoesNotExist(string $other)
    {
        $customMessage = $this->faker()->sentence();

        $constraint = new InterfaceExists();

        try {
            $constraint->evaluate($other, $customMessage);
        } catch (Framework\AssertionFailedError $exception) {
            $expectedMessage = <<<TXT
$customMessage
Failed asserting that an interface "$other" exists.
TXT;

            $this->assertSame($expectedMessage, $exception->getMessage());

            return;
        }

        $this->fail();
    }

    public function testEvaluateReturnsNullIfInterfaceExists()
    {
        $other = Fixture\InterfaceExists\ExampleInterface::class;

        $constraint = new InterfaceExists();

        $this->assertNull($constraint->evaluate($other));
    }
}
