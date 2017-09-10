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

    /**
     * @dataProvider providerNotClass
     *
     * @param string $className
     */
    public function testAssertClassExistsFailsWhenClassIsNotClass(string $className)
    {
        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a class "%s" exists.',
            $className
        ));

        $this->assertClassExists($className);
    }

    public function providerNotClass(): \Generator
    {
        $classNames = [
            'class-non-existent' => __NAMESPACE__ . '\Fixture\NonExistentClass',
            'interface' => Fixture\NotClass\ExampleInterface::class,
            'trait' => Fixture\NotClass\ExampleTrait::class,
        ];

        foreach ($classNames as $className) {
            yield [
                $className,
            ];
        }
    }

    public function testAssertClassExistsSucceedsWhenClassExists()
    {
        $className = Fixture\ClassExists\ExampleClass::class;

        $this->assertClassExists($className);
    }

    /**
     * @dataProvider providerNotClass
     *
     * @param string $parentClassName
     */
    public function testAssertClassExtendsFailsWhenParentClassIsNotClass(string $parentClassName)
    {
        $className = Fixture\ClassExtends\ChildClass::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a class "%s" exists.',
            $parentClassName
        ));

        $this->assertClassExtends(
            $parentClassName,
            $className
        );
    }

    /**
     * @dataProvider providerNotClass
     *
     * @param string $className
     */
    public function testAssertClassExtendsFailsWhenClassIsNotClass(string $className)
    {
        $parentClassName = Fixture\ClassExtends\ParentClass::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a class "%s" exists.',
            $className
        ));

        $this->assertClassExtends(
            $parentClassName,
            $className
        );
    }

    public function testClassExtendsFailsWhenClassDoesNotExtendParentClass()
    {
        $parentClassName = Fixture\ClassExtends\ParentClass::class;
        $className = Fixture\ClassExtends\UnrelatedClass::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that class "%s" extends "%s".',
            $className,
            $parentClassName
        ));

        $this->assertClassExtends(
            $parentClassName,
            $className
        );
    }

    public function testAssertClassExtendsSucceedsWhenClassExtendsParentClass()
    {
        $parentClassName = Fixture\ClassExtends\ParentClass::class;
        $className = Fixture\ClassExtends\ChildClass::class;

        $this->assertClassExtends(
            $parentClassName,
            $className
        );
    }

    /**
     * @dataProvider providerNotInterface
     *
     * @param string $interfaceName
     */
    public function testAssertClassImplementsInterfaceFailsWhenInterfaceIsNotInterface(string $interfaceName)
    {
        $className = Fixture\ImplementsInterface\ClassImplementingInterface::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that an interface "%s" exists.',
            $interfaceName
        ));

        $this->assertClassImplementsInterface(
            $interfaceName,
            $className
        );
    }

    /**
     * @dataProvider providerNotClass
     *
     * @param string $className
     */
    public function testAssertClassImplementsInterfaceFailsWhenClassIsNotClass(string $className)
    {
        $interfaceName = Fixture\ImplementsInterface\ExampleInterface::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a class "%s" exists.',
            $className
        ));

        $this->assertClassImplementsInterface(
            $interfaceName,
            $className
        );
    }

    public function testAssertClassImplementsInterfaceFailsWhenClassDoesNotImplementInterface()
    {
        $interfaceName = Fixture\ImplementsInterface\ExampleInterface::class;
        $className = Fixture\ImplementsInterface\ClassNotImplementingInterface::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that class "%s" implements interface "%s".',
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
        $interfaceName = Fixture\ImplementsInterface\ExampleInterface::class;
        $className = Fixture\ImplementsInterface\ClassImplementingInterface::class;

        $this->assertClassImplementsInterface(
            $interfaceName,
            $className
        );
    }

    /**
     * @dataProvider providerNotClass
     *
     * @param string $className
     */
    public function testAssertClassIsAbstractOrFinalFailsWhenClassIsNotClass(string $className)
    {
        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a class "%s" exists.',
            $className
        ));

        $this->assertClassIsAbstractOrFinal($className);
    }

    public function testAssertClassIsAbstractOrFinalFailsWhenClassIsNeitherAbstractNorFinal()
    {
        $className = Fixture\ClassIsAbstractOrFinal\NeitherAbstractNorFinalClass::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that class "%s" is abstract or final.',
            $className
        ));

        $this->assertClassIsAbstractOrFinal($className);
    }

    public function providerClassAbstractOrFinal(): \Generator
    {
        $classNames = [
            'class-abstract' => Fixture\ClassIsAbstractOrFinal\AbstractClass::class,
            'class-final' => Fixture\ClassIsAbstractOrFinal\FinalClass::class,
        ];

        foreach ($classNames as $key => $className) {
            yield $key => [
                $className,
            ];
        }
    }

    /**
     * @dataProvider providerNotTrait
     *
     * @param string $traitName
     */
    public function testAssertClassUsesTraitFailsWhenTraitIsNotTrait(string $traitName)
    {
        $className = Fixture\ClassUsesTrait\ClassUsingTrait::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a trait "%s" exists.',
            $traitName
        ));

        $this->assertClassUsesTrait(
            $traitName,
            $className
        );
    }

    /**
     * @dataProvider providerNotClass
     *
     * @param string $className
     */
    public function testAssertClassUsesTraitFailsWhenClassIsNotClass(string $className)
    {
        $traitName = Fixture\ClassUsesTrait\ExampleTrait::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a class "%s" exists.',
            $className
        ));

        $this->assertClassUsesTrait(
            $traitName,
            $className
        );
    }

    public function testAssertClassUsesTraitFailsWhenClassDoesNotUseTrait()
    {
        $traitName = Fixture\ClassUsesTrait\ExampleTrait::class;
        $className = Fixture\ClassUsesTrait\ClassNotUsingTrait::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that class "%s" uses trait "%s".',
            $className,
            $traitName
        ));

        $this->assertClassUsesTrait(
            $traitName,
            $className
        );
    }

    public function testAssertClassUsesTraitSucceedsWhenClassUsesTrait()
    {
        $traitName = Fixture\ClassUsesTrait\ExampleTrait::class;
        $className = Fixture\ClassUsesTrait\ClassUsingTrait::class;

        $this->assertClassUsesTrait(
            $traitName,
            $className
        );
    }

    /**
     * @dataProvider providerNotInterface
     *
     * @param string $interfaceName
     */
    public function testAssertInterfaceExistsFailsWhenInterfaceIsNotInterface(string $interfaceName)
    {
        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that an interface "%s" exists.',
            $interfaceName
        ));

        $this->assertInterfaceExists($interfaceName);
    }

    public function providerNotInterface(): \Generator
    {
        $interfaceNames = [
            'class' => Fixture\NotInterface\ExampleClass::class,
            'interface-non-existent' => __NAMESPACE__ . '\Fixture\NonExistentInterface',
            'trait' => Fixture\NotInterface\ExampleTrait::class,
        ];

        foreach ($interfaceNames as $key => $interfaceName) {
            yield $key => [
                $interfaceName,
            ];
        }
    }

    public function testAssertInterfaceExistsSucceedsWhenInterfaceExists()
    {
        $interfaceName = Fixture\InterfaceExists\ExampleInterface::class;

        $this->assertInterfaceExists($interfaceName);
    }

    /**
     * @dataProvider providerNotInterface
     *
     * @param string $parentInterfaceName
     */
    public function testInterfaceExtendsFailsWhenParentInterfaceIsNotInterface(string $parentInterfaceName)
    {
        $interfaceName = Fixture\InterfaceExtends\ChildInterface::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that an interface "%s" exists.',
            $parentInterfaceName
        ));

        $this->assertInterfaceExtends(
            $parentInterfaceName,
            $interfaceName
        );
    }

    /**
     * @dataProvider providerNotInterface
     *
     * @param string $interfaceName
     */
    public function testAssertInterfaceExtendsFailsWhenInterfaceIsNotInterface(string $interfaceName)
    {
        $parentInterfaceName = Fixture\InterfaceExtends\ParentInterface::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that an interface "%s" exists.',
            $interfaceName
        ));

        $this->assertInterfaceExtends(
            $parentInterfaceName,
            $interfaceName
        );
    }

    public function testAssertInterfaceExtendsFailsWhenInterfaceDoesNotExtendParentInterface()
    {
        $parentInterfaceName = Fixture\InterfaceExtends\ParentInterface::class;
        $interfaceName = Fixture\InterfaceExtends\UnrelatedInterface::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that interface "%s" extends "%s".',
            $interfaceName,
            $parentInterfaceName
        ));

        $this->assertInterfaceExtends(
            $parentInterfaceName,
            $interfaceName
        );
    }

    public function testAssertInterfaceExtendsSucceedsWhenInterfaceExtendsParentInterface()
    {
        $parentInterfaceName = Fixture\InterfaceExtends\ParentInterface::class;
        $interfaceName = Fixture\InterfaceExtends\ChildInterface::class;

        $this->assertInterfaceExtends(
            $parentInterfaceName,
            $interfaceName
        );
    }

    /**
     * @dataProvider providerNotTrait
     *
     * @param string $traitName
     */
    public function testAssertTraitExistsFailsWhenTraitIsNotTrait(string $traitName)
    {
        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed to assert that a trait "%s" exists.',
            $traitName
        ));

        $this->assertTraitExists($traitName);
    }

    public function providerNotTrait(): \Generator
    {
        $traitNames = [
            'class' => Fixture\NotTrait\ExampleClass::class,
            'interface' => Fixture\NotTrait\ExampleInterface::class,
            'trait-non-existent' => __NAMESPACE__ . '\Fixture\NonExistentTrait',
        ];

        foreach ($traitNames as $key => $traitName) {
            yield $key => [
                $traitName,
            ];
        }
    }

    public function testAssertTraitExistsSucceedsWhenTraitExists()
    {
        $traitName = Fixture\TraitExists\ExampleTrait::class;

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
