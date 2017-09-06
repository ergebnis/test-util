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

namespace Localheinz\Test\Util\Test\Unit;

use Faker\Factory;
use Faker\Generator;
use Faker\Provider;
use Localheinz\Test\Util\Helper;
use PHPUnit\Framework;

final class HelperTest extends Framework\TestCase
{
    use Helper;

    public function testFakerWithoutLocaleReturnsFakerWithDefaultLocale()
    {
        $faker = $this->faker();

        $this->assertHasOnlyProvidersWithLocale(Factory::DEFAULT_LOCALE, $faker);
    }

    /**
     * @dataProvider providerLocale
     *
     * @param string $locale
     */
    public function testFakerWithLocaleReturnsFakerWithSpecifiedLocale(string $locale)
    {
        $faker = $this->faker($locale);

        $this->assertHasOnlyProvidersWithLocale($locale, $faker);
    }

    /**
     * @dataProvider providerLocale
     *
     * @param string $locale
     */
    public function testFakerReturnsSameFaker(string $locale)
    {
        $faker = $this->faker($locale);

        $this->assertSame($faker, $this->faker($locale));
    }

    public function providerLocale(): \Generator
    {
        /**
         * Note that \Faker\Factory::getProviderClassname() will fall back to using the default locale if it cannot find
         * a localized provider class name for one of the default providers - that's why the selection of locales here
         * is a bit limited.
         *
         * @see \Faker\Factory::$defaultProviders
         * @see \Faker\Factory::getProviderClassname()
         */
        $locales = [
            'de_DE',
            'en_US',
            'fr_FR',
        ];

        foreach ($locales as $locale) {
            yield $locale => [
                $locale,
            ];
        }
    }

    public function testClassExistsFailsWhenClassDoesNotExist()
    {
        $className = __NAMESPACE__ . '\Fixture\NonExistentClass';

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a class "%s" exists',
            $className
        ));

        $this->assertClassExists($className);
    }

    /**
     * @dataProvider providerNotAClass
     *
     * @param string $className
     */
    public function testClassExistsFailsWhenClassIsNotAClass(string $className)
    {
        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a class "%s" exists',
            $className
        ));

        $this->assertClassExists($className);
    }

    public function providerNotAClass(): \Generator
    {
        $classNames = [
            Fixture\AnInterface::class,
            Fixture\ATrait::class,
        ];

        foreach ($classNames as $className) {
            yield [
                $className,
            ];
        }
    }

    public function testClassExistsSucceedsWhenClassExists()
    {
        $className = Fixture\AClass::class;

        $this->assertClassExists($className);
    }

    public function testInterfaceExistsFailsWhenInterfaceDoesNotExist()
    {
        $className = __NAMESPACE__ . '\Fixture\NonExistentInterface';

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that an interface "%s" exists',
            $className
        ));

        $this->assertInterfaceExists($className);
    }

    /**
     * @dataProvider providerNotAnInterface
     *
     * @param string $className
     */
    public function testInterfaceExistsFailsWhenInterfaceIsNotAInterface(string $className)
    {
        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that an interface "%s" exists',
            $className
        ));

        $this->assertInterfaceExists($className);
    }

    public function providerNotAnInterface(): \Generator
    {
        $classNames = [
            Fixture\AClass::class,
            Fixture\ATrait::class,
        ];

        foreach ($classNames as $className) {
            yield [
                $className,
            ];
        }
    }

    public function testInterfaceExistsSucceedsWhenInterfaceExists()
    {
        $className = Fixture\AnInterface::class;

        $this->assertInterfaceExists($className);
    }

    public function testTraitExistsFailsWhenTraitDoesNotExist()
    {
        $className = __NAMESPACE__ . '\Fixture\NonExistentTrait';

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a trait "%s" exists',
            $className
        ));

        $this->assertTraitExists($className);
    }

    /**
     * @dataProvider providerNotATrait
     *
     * @param string $className
     */
    public function testTraitExistsFailsWhenTraitIsNotATrait(string $className)
    {
        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a trait "%s" exists',
            $className
        ));

        $this->assertTraitExists($className);
    }

    public function providerNotATrait(): \Generator
    {
        $classNames = [
            Fixture\AClass::class,
            Fixture\AnInterface::class,
        ];

        foreach ($classNames as $className) {
            yield [
                $className,
            ];
        }
    }

    public function testTraitExistsSucceedsWhenTraitExists()
    {
        $className = Fixture\ATrait::class;

        $this->assertTraitExists($className);
    }

    private function assertHasOnlyProvidersWithLocale(string $locale, Generator $faker)
    {
        $providerClasses = \array_map(function (Provider\Base $provider) {
            return \get_class($provider);
        }, $faker->getProviders());

        $providerLocales = \array_map(function (string $providerClass) {
            if (0 === \preg_match('/^Faker\\\\Provider\\\\(?P<locale>[a-z]{2}_[A-Z]{2})\\\\/', $providerClass, $matches)) {
                return null;
            }

            return $matches['locale'];
        }, $providerClasses);

        $locales = \array_values(\array_unique(\array_filter($providerLocales)));

        $expected = [
            $locale,
        ];

        $this->assertEquals($expected, $locales);
    }
}
