<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2020 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/test-util
 */

namespace Ergebnis\Test\Util\Test\Unit\DataProvider;

use Ergebnis\Test\Util;
use PHPUnit\Framework;

/**
 * @internal
 */
abstract class AbstractProviderTestCase extends Framework\TestCase
{
    use Util\Helper;

    /**
     * @param array<string, mixed>             $values
     * @param \Generator<string, array<mixed>> $provider
     */
    final protected static function assertProvidesDataForValues(array $values, \Generator $provider): void
    {
        self::assertExpectedValuesAreNotEmpty($values);

        $expected = \iterator_to_array(self::provideDataForValues($values));

        $provided = \iterator_to_array($provider);

        self::assertProvidedDataIsNotEmpty($provided);

        self::assertEquals(
            $expected,
            $provided,
            'Failed asserting that a generator yields data for expected values.'
        );
    }

    /**
     * @param \Closure                         $test
     * @param \Generator<string, array<mixed>> $provider
     */
    final protected static function assertProvidesDataForValuesWhere(\Closure $test, \Generator $provider): void
    {
        $provided = \iterator_to_array($provider);

        self::assertProvidedDataIsNotEmpty($provided);
        self::assertProvidedDataContainsArraysWhereFirstElementPassesTest($test, $provided);
    }

    /**
     * @param \Closure                         $test
     * @param \Generator<string, array<mixed>> $provider
     */
    final protected static function assertProvidesDataForValuesWhereNot(\Closure $test, \Generator $provider): void
    {
        $provided = \iterator_to_array($provider);

        self::assertProvidedDataIsNotEmpty($provided);
        self::assertProvidedDataContainsArraysWhereFirstElementDoesNotPassTest($test, $provided);
    }

    /**
     * @param array $actual
     */
    final protected static function assertProvidedDataIsNotEmpty(array $actual): void
    {
        self::assertNotEmpty($actual, 'Failed asserting that provided values are not empty.');
    }

    /**
     * @param array $values
     */
    private static function assertExpectedValuesAreNotEmpty(array $values): void
    {
        self::assertNotEmpty($values, 'Failed asserting that expected values are not empty.');
    }

    /**
     * @param \Closure             $test
     * @param array<string, mixed> $provided
     */
    private static function assertProvidedDataContainsArraysWhereFirstElementPassesTest(\Closure $test, array $provided): void
    {
        self::assertProvidedDataContainsArraysWithOneElement($provided);

        $value = \array_map(static function (array $set) {
            return \array_shift($set);
        }, $provided);

        $tested = \array_filter($value, static function ($value) use ($test): bool {
            return true === $test($value);
        });

        self::assertEquals(
            $value,
            $tested,
            'Failed asserting that the first value in each array passed the test.'
        );
    }

    /**
     * @param \Closure             $test
     * @param array<string, mixed> $provided
     */
    private static function assertProvidedDataContainsArraysWhereFirstElementDoesNotPassTest(\Closure $test, array $provided): void
    {
        self::assertProvidedDataContainsArraysWithOneElement($provided);

        $value = \array_map(static function (array $set) {
            return \array_shift($set);
        }, $provided);

        $tested = \array_filter($value, static function ($value) use ($test): bool {
            return false === $test($value);
        });

        self::assertEquals(
            $value,
            $tested,
            'Failed asserting that the first value in each array does not pass the test.'
        );
    }

    /**
     * @param array<string, mixed> $provided
     */
    private static function assertProvidedDataContainsArraysWithOneElement(array $provided): void
    {
        self::assertProvidedDataContainsArraysOnly($provided);

        $setsWhereNumberOfProvidedArgumentsIsNotOne = \array_filter($provided, static function (array $set): bool {
            return 1 !== \count($set);
        });

        self::assertEquals(
            [],
            $setsWhereNumberOfProvidedArgumentsIsNotOne,
            'Failed asserting that each set in the provided data contains only a single value.'
        );
    }

    /**
     * @param array<string, mixed> $provided
     */
    private static function assertProvidedDataContainsArraysOnly(array $provided): void
    {
        $values = \array_filter($provided, static function ($set): bool {
            return !\is_array($set);
        });

        self::assertEquals(
            [],
            $values,
            'Failed asserting that each value is an array.'
        );
    }
}
