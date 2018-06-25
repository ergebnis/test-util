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

namespace Localheinz\Test\Util\Test\Unit;

use Localheinz\Test\Util\Helper;
use Localheinz\Test\Util\Test\Fixture;
use PHPUnit\Framework;

/**
 * @internal
 */
final class ProjectCodeTest extends Framework\TestCase
{
    use Helper;

    public function testProductionClassesAreAbstractOrFinal(): void
    {
        $this->assertClassesAreAbstractOrFinal(__DIR__ . '/../../src');
    }

    public function testProductionClassesHaveTests(): void
    {
        $this->assertClassesHaveTests(
            __DIR__ . '/../../src',
            'Localheinz\\Test\\Util\\',
            'Localheinz\\Test\\Util\\Test\\Unit\\'
        );
    }

    public function testTestClassesAreAbstractOrFinal(): void
    {
        $this->assertClassesAreAbstractOrFinal(__DIR__ . '/..', [
            Fixture\ClassesAreAbstractOrFinal\NotAllAbstractOrFinal\AlsoNeitherAbstractNorFinal::class,
            Fixture\ClassesAreAbstractOrFinal\NotAllAbstractOrFinal\NeitherAbstractNorFinal::class,
            Fixture\ClassIsAbstract\ConcreteClass::class,
            Fixture\ClassIsFinal\NeitherAbstractNorFinalClass::class,
        ]);
    }
}
