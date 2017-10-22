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

use Localheinz\Test\Util\Constraint\TraitExists;
use Localheinz\Test\Util\Test\Fixture;
use PHPUnit\Framework;

final class TraitExistsTest extends AbstractTestCase
{
    public function testCountReturnsOne()
    {
        $constraint = new TraitExists();

        $this->assertCount(1, $constraint);
    }

    public function testToStringReturnsDescription()
    {
        $constraint = new TraitExists();

        $this->assertSame('trait exists', $constraint->toString());
    }

    /**
     * @dataProvider \Localheinz\Test\Util\Test\Unit\DataProvider::providerNotTrait()
     *
     * @param string $other
     */
    public function testEvaluateReturnsFalseIfTraitDoesNotExist(string $other)
    {
        $constraint = new TraitExists();

        $this->assertFalse($constraint->evaluate($other, '', true));
    }

    public function testEvaluateReturnsTrueIfTraitExists()
    {
        $other = Fixture\TraitExists\ExampleTrait::class;

        $constraint = new TraitExists();

        $this->assertTrue($constraint->evaluate($other, '', true));
    }

    /**
     * @dataProvider \Localheinz\Test\Util\Test\Unit\DataProvider::providerNotTrait()
     *
     * @param string $other
     */
    public function testEvaluateThrowsAssertionFailedErrorWithDefaultMessageWhenTraitDoesNotExist(string $other)
    {
        $constraint = new TraitExists();

        try {
            $constraint->evaluate($other);
        } catch (Framework\AssertionFailedError $exception) {
            $expectedMessage = <<<TXT
Failed asserting that a trait "$other" exists.
TXT;

            $this->assertSame($expectedMessage, $exception->getMessage());

            return;
        }

        $this->fail();
    }

    /**
     * @dataProvider \Localheinz\Test\Util\Test\Unit\DataProvider::providerNotTrait()
     *
     * @param string $other
     */
    public function testEvaluateThrowsAssertionFailedErrorWithCustomMessageWhenTraitDoesNotExist(string $other)
    {
        $customMessage = $this->faker()->sentence();

        $constraint = new TraitExists();

        try {
            $constraint->evaluate($other, $customMessage);
        } catch (Framework\AssertionFailedError $exception) {
            $expectedMessage = <<<TXT
$customMessage
Failed asserting that a trait "$other" exists.
TXT;

            $this->assertSame($expectedMessage, $exception->getMessage());

            return;
        }

        $this->fail();
    }

    public function testEvaluateReturnsNullWhenTraitExists()
    {
        $other = Fixture\TraitExists\ExampleTrait::class;

        $constraint = new TraitExists();

        $this->assertNull($constraint->evaluate($other));
    }
}
