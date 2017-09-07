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

    public function testAssertClassExistsFailsWhenClassDoesNotExist()
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
    public function testAssertClassExistsFailsWhenClassIsNotAClass(string $className)
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
            Fixture\ExampleInterface::class,
            Fixture\ExampleTrait::class,
        ];

        foreach ($classNames as $className) {
            yield [
                $className,
            ];
        }
    }

    public function testAssertClassExistsSucceedsWhenClassExists()
    {
        $className = Fixture\ExampleClass::class;

        $this->assertClassExists($className);
    }

    public function testAssertClassImplementsInterfaceFailsWhenInterfaceDoesNotExist()
    {
        $interfaceName = __NAMESPACE__ . '\Fixture\NonExistentInterface';
        $className = Fixture\ExampleClass::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that an interface "%s" exists',
            $interfaceName
        ));

        $this->assertClassImplementsInterface(
            $interfaceName,
            $className
        );
    }

    public function testAssertClassImplementsInterfaceFailsWhenClassDoesNotExist()
    {
        $interfaceName = Fixture\ExampleInterface::class;
        $className = __NAMESPACE__ . '\Fixture\NonExistentClass';

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a class "%s" exists',
            $className
        ));

        $this->assertClassImplementsInterface(
            $interfaceName,
            $className
        );
    }

    public function testAssertClassImplementsInterfaceFailsWhenClassDoesNotImplementInterface()
    {
        $interfaceName = Fixture\ExampleInterface::class;
        $className = Fixture\ClassNotImplementingInterface::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that class "%s" implements interface "%s"',
            $className,
            $interfaceName
        ));

        $this->assertClassImplementsInterface(
            $interfaceName,
            $className
        );
    }

    public function testAssertClassImplementsInterfaceSucceedsWhenClassImplementsInterface()
    {
        $interfaceName = Fixture\ExampleInterface::class;
        $className = Fixture\ClassImplementingInterface::class;

        $this->assertClassImplementsInterface(
            $interfaceName,
            $className
        );
    }

    public function testAssertClassIsAbstractOrFinalFailsWhenClassDoesNotExist()
    {
        $className = __NAMESPACE__ . '\Fixture\NonExistentClass';

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a class "%s" exists',
            $className
        ));

        $this->assertClassIsAbstractOrFinal($className);
    }

    /**
     * @dataProvider providerNotAClass
     *
     * @param string $className
     */
    public function testAssertClassIsAbstractOrFinalFailsWhenClassIsNotAClass(string $className)
    {
        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a class "%s" exists',
            $className
        ));

        $this->assertClassIsAbstractOrFinal($className);
    }

    public function testAssertClassIsAbstractOrFinalFailsWhenClassIsNeitherAbstractNorFinal()
    {
        $className = Fixture\NonFinalClass::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that class "%s" is abstract or final',
            $className
        ));

        $this->assertClassIsAbstractOrFinal($className);
    }

    public function providerClassAbstractOrFinal(): \Generator
    {
        $classNames = [
            Fixture\AbstractClass::class,
            Fixture\FinalClass::class,
        ];

        foreach ($classNames as $className) {
            yield [
                $className,
            ];
        }
    }

    public function testAssertInterfaceExistsFailsWhenInterfaceDoesNotExist()
    {
        $interfaceName = __NAMESPACE__ . '\Fixture\NonExistentInterface';

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that an interface "%s" exists',
            $interfaceName
        ));

        $this->assertInterfaceExists($interfaceName);
    }

    /**
     * @dataProvider providerNotAnInterface
     *
     * @param string $interfaceName
     */
    public function testAssertInterfaceExistsFailsWhenInterfaceIsNotAnInterface(string $interfaceName)
    {
        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that an interface "%s" exists',
            $interfaceName
        ));

        $this->assertInterfaceExists($interfaceName);
    }

    public function providerNotAnInterface(): \Generator
    {
        $interfaceNames = [
            Fixture\ExampleClass::class,
            Fixture\ExampleTrait::class,
        ];

        foreach ($interfaceNames as $interfaceName) {
            yield [
                $interfaceName,
            ];
        }
    }

    public function testAssertInterfaceExistsSucceedsWhenInterfaceExists()
    {
        $interfaceName = Fixture\ExampleInterface::class;

        $this->assertInterfaceExists($interfaceName);
    }

    public function testAssertTraitExistsFailsWhenTraitDoesNotExist()
    {
        $traitName = __NAMESPACE__ . '\Fixture\NonExistentTrait';

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a trait "%s" exists',
            $traitName
        ));

        $this->assertTraitExists($traitName);
    }

    /**
     * @dataProvider providerNotATrait
     *
     * @param string $traitName
     */
    public function testAssertTraitExistsFailsWhenTraitIsNotATrait(string $traitName)
    {
        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a trait "%s" exists',
            $traitName
        ));

        $this->assertTraitExists($traitName);
    }

    public function providerNotATrait(): \Generator
    {
        $traitNames = [
            Fixture\ExampleClass::class,
            Fixture\ExampleInterface::class,
        ];

        foreach ($traitNames as $traitName) {
            yield [
                $traitName,
            ];
        }
    }

    public function testAssertTraitExistsSucceedsWhenTraitExists()
    {
        $traitName = Fixture\ExampleTrait::class;

        $this->assertTraitExists($traitName);
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
