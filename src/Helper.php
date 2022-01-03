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

namespace Ergebnis\Test\Util;

use Faker\Factory;
use Faker\Generator;

trait Helper
{
    /**
     * Returns a localized instance of Faker\Generator.
     *
     * Useful for generating fake data in tests.
     *
     * @see https://github.com/fzaninotto/Faker
     */
    final protected static function faker(string $locale = 'en_US'): Generator
    {
        /**
         * @var array<string, Generator>
         */
        static $fakers = [];

        if (!\array_key_exists($locale, $fakers)) {
            $faker = Factory::create($locale);

            $faker->seed(9001);

            $fakers[$locale] = $faker;
        }

        return $fakers[$locale];
    }

    /**
     * @deprecated use ergebnis/data-provider instead
     * @see https://github.com/ergebnis/data-provider
     *
     * @param array<string, mixed> $values
     *
     * @throws Exception\EmptyValues
     *
     * @return \Generator<string, array{0: mixed}>
     */
    final protected static function provideDataForValues(array $values): \Generator
    {
        if ([] === $values) {
            throw Exception\EmptyValues::create();
        }

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    /**
     * @deprecated use ergebnis/data-provider instead
     * @see https://github.com/ergebnis/data-provider
     *
     * @param array<string, mixed> $values
     *
     * @throws Exception\EmptyValues
     *
     * @return \Generator<string, array{0: mixed}>
     */
    final protected static function provideDataForValuesWhere(array $values, \Closure $test): \Generator
    {
        if ([] === $values) {
            throw Exception\EmptyValues::create();
        }

        $filtered = \array_filter($values, static function ($value) use ($test): bool {
            return true === $test($value);
        });

        if ([] === $filtered) {
            throw Exception\EmptyValues::filtered();
        }

        yield from self::provideDataForValues($filtered);
    }

    /**
     * @deprecated use ergebnis/data-provider instead
     * @see https://github.com/ergebnis/data-provider
     *
     * @param array<string, mixed> $values
     *
     * @throws Exception\EmptyValues
     *
     * @return \Generator<string, array{0: mixed}>
     */
    final protected static function provideDataForValuesWhereNot(array $values, \Closure $test): \Generator
    {
        if ([] === $values) {
            throw Exception\EmptyValues::create();
        }

        $filtered = \array_filter($values, static function ($value) use ($test): bool {
            return false === $test($value);
        });

        if ([] === $filtered) {
            throw Exception\EmptyValues::filtered();
        }

        yield from self::provideDataForValues($filtered);
    }
}
