<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/test-util
 */

namespace Ergebnis\Test\Util\Test\AutoReview;

use Ergebnis\Test\Util\Helper;
use Ergebnis\Test\Util\Test\Fixture;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @coversNothing
 */
final class TestCodeTest extends Framework\TestCase
{
    use Helper;

    public function testTestClassesAreAbstractOrFinal(): void
    {
        self::assertClassesAreAbstractOrFinal(__DIR__ . '/..', [
            Fixture\ClassesAreAbstractOrFinal\NotAllAbstractOrFinal\AlsoNeitherAbstractNorFinal::class,
            Fixture\ClassesAreAbstractOrFinal\NotAllAbstractOrFinal\NeitherAbstractNorFinal::class,
            Fixture\ClassIsAbstract\ConcreteClass::class,
            Fixture\ClassIsFinal\NeitherAbstractNorFinalClass::class,
        ]);
    }
}
