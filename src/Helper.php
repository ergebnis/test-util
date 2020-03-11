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

namespace Ergebnis\Test\Util;

use Ergebnis\Classy;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework;

trait Helper
{
    /**
     * Returns a localized instance of Faker\Generator.
     *
     * Useful for generating fake data in tests.
     *
     * @see https://github.com/fzaninotto/Faker
     *
     * @param string $locale
     *
     * @return Generator
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
     * Asserts that classes in a directory are either abstract or final.
     *
     * Useful to prevent long inheritance chains.
     *
     * @param string         $directory
     * @param class-string[] $excludeClassNames
     *
     * @throws Exception\NonExistentDirectory
     * @throws Exception\InvalidExcludeClassName
     * @throws Exception\NonExistentExcludeClass
     * @throws Classy\Exception\MultipleDefinitionsFound
     */
    final protected static function assertClassesAreAbstractOrFinal(string $directory, array $excludeClassNames = []): void
    {
        self::assertClassyConstructsSatisfySpecification(
            static function (string $className): bool {
                /** @var class-string $className */
                $reflection = new \ReflectionClass($className);

                return $reflection->isAbstract()
                    || $reflection->isFinal()
                    || $reflection->isInterface()
                    || $reflection->isTrait();
            },
            $directory,
            $excludeClassNames,
            "Failed asserting that the classes\n\n%s\n\nare abstract or final."
        );
    }

    /**
     * Asserts that classes in a directory have matching test classes extending from PHPUnit\Framework\TestCase.
     *
     * @param string         $directory
     * @param string         $namespace
     * @param string         $testNamespace
     * @param class-string[] $excludeClassyNames
     *
     * @throws Exception\NonExistentDirectory
     * @throws Exception\InvalidExcludeClassName
     * @throws Exception\NonExistentExcludeClass
     * @throws Classy\Exception\MultipleDefinitionsFound
     */
    final protected static function assertClassesHaveTests(string $directory, string $namespace, string $testNamespace, array $excludeClassyNames = []): void
    {
        if (!\is_dir($directory)) {
            throw Exception\NonExistentDirectory::fromDirectory($directory);
        }

        \array_walk($excludeClassyNames, static function ($excludeClassyName): void {
            if (!\is_string($excludeClassyName)) {
                throw Exception\InvalidExcludeClassName::fromClassName($excludeClassyName);
            }

            if (!\class_exists($excludeClassyName)) {
                throw Exception\NonExistentExcludeClass::fromClassName($excludeClassyName);
            }
        });

        $constructs = Classy\Constructs::fromDirectory($directory);

        /** @var class-string[] $classyNames */
        $classyNames = \array_diff(
            \array_map(static function (Classy\Construct $construct): string {
                return $construct->name();
            }, $constructs),
            $excludeClassyNames
        );

        $namespace = \rtrim($namespace, '\\') . '\\';
        $testNamespace = \rtrim($testNamespace, '\\') . '\\';

        $testClassNameFrom = static function (string $className) use ($namespace, $testNamespace): string {
            return \str_replace(
                $namespace,
                $testNamespace,
                $className
            ) . 'Test';
        };

        $classesWithoutTests = \array_filter($classyNames, static function (string $className) use ($testClassNameFrom): bool {
            /** @var class-string $className */
            $reflection = new \ReflectionClass($className);

            /**
             * Construct is not concrete, does not need a test.
             */
            if ($reflection->isAbstract() || $reflection->isInterface() || $reflection->isTrait()) {
                return false;
            }

            /**
             * Class is a test itself.
             */
            if ($reflection->isSubclassOf(Framework\TestCase::class)) {
                return false;
            }

            $testClassName = $testClassNameFrom($className);

            if (\class_exists($testClassName)) {
                /** @var class-string $testClassName */
                $testReflection = new \ReflectionClass($testClassName);

                if ($testReflection->isSubclassOf(Framework\TestCase::class) && $testReflection->isInstantiable()) {
                    return false;
                }
            }

            return true;
        });

        self::assertEmpty($classesWithoutTests, \sprintf(
            "Failed asserting that the classes\n\n%s\n\nhave tests. Expected corresponding test classes\n\n%s\n\nextending from \"%s\" but could not find them.",
            \implode("\n", \array_map(static function (string $className): string {
                return \sprintf(
                    ' - %s',
                    $className
                );
            }, $classesWithoutTests)),
            \implode("\n", \array_map(static function (string $className) use ($testClassNameFrom): string {
                return \sprintf(
                    ' - %s',
                    $testClassNameFrom($className)
                );
            }, $classesWithoutTests)),
            Framework\TestCase::class
        ));
    }

    /**
     * Asserts that all classes, interfaces, and traits found in a directory satisfy a specification.
     *
     * Useful for asserting that production and test code conforms to certain requirements.
     *
     * The specification will be invoked with a single argument, the class name, and should return true or false.
     *
     * @param callable       $specification
     * @param string         $directory
     * @param class-string[] $excludeClassyNames
     * @param string         $message
     *
     * @throws Exception\NonExistentDirectory
     * @throws Exception\InvalidExcludeClassName
     * @throws Exception\NonExistentExcludeClass
     * @throws Classy\Exception\MultipleDefinitionsFound
     */
    final protected static function assertClassyConstructsSatisfySpecification(callable $specification, string $directory, array $excludeClassyNames = [], string $message = ''): void
    {
        if (!\is_dir($directory)) {
            throw Exception\NonExistentDirectory::fromDirectory($directory);
        }

        \array_walk($excludeClassyNames, static function ($excludeClassyName): void {
            if (!\is_string($excludeClassyName)) {
                throw Exception\InvalidExcludeClassName::fromClassName($excludeClassyName);
            }

            if (!\class_exists($excludeClassyName)) {
                throw Exception\NonExistentExcludeClass::fromClassName($excludeClassyName);
            }
        });

        $constructs = Classy\Constructs::fromDirectory($directory);

        $classyNames = \array_diff(
            $constructs,
            $excludeClassyNames
        );

        $classyNamesNotSatisfyingSpecification = \array_filter($classyNames, static function (string $className) use ($specification): bool {
            return false === $specification($className);
        });

        self::assertEmpty($classyNamesNotSatisfyingSpecification, \sprintf(
            '' !== $message ? $message : "Failed asserting that the classy constructs\n\n%s\n\nsatisfy a specification.",
            ' - ' . \implode("\n - ", $classyNamesNotSatisfyingSpecification)
        ));
    }

    /**
     * Asserts that a class exists.
     *
     * @param string $className
     */
    final protected static function assertClassExists(string $className): void
    {
        self::assertTrue(\class_exists($className), \sprintf(
            'Failed asserting that a class "%s" exists.',
            $className
        ));
    }

    /**
     * Asserts that a class extends from a parent class.
     *
     * @param class-string $parentClassName
     * @param class-string $className
     */
    final protected static function assertClassExtends(string $parentClassName, string $className): void
    {
        self::assertClassExists($parentClassName);
        self::assertClassExists($className);

        /** @var class-string $className */
        $reflection = new \ReflectionClass($className);

        self::assertTrue($reflection->isSubclassOf($parentClassName), \sprintf(
            'Failed asserting that class "%s" extends "%s".',
            $className,
            $parentClassName
        ));
    }

    /**
     * Asserts that a class implements an interface.
     *
     * @param class-string $interfaceName
     * @param class-string $className
     */
    final protected static function assertClassImplementsInterface(string $interfaceName, string $className): void
    {
        self::assertInterfaceExists($interfaceName);
        self::assertClassExists($className);

        /** @var class-string $className */
        $reflection = new \ReflectionClass($className);

        self::assertTrue($reflection->implementsInterface($interfaceName), \sprintf(
            'Failed asserting that class "%s" implements interface "%s".',
            $className,
            $interfaceName
        ));
    }

    /**
     * Asserts that a class is abstract.
     *
     * @param class-string $className
     */
    final protected static function assertClassIsAbstract(string $className): void
    {
        self::assertClassExists($className);

        /** @var class-string $className */
        $reflection = new \ReflectionClass($className);

        self::assertTrue($reflection->isAbstract(), \sprintf(
            'Failed asserting that class "%s" is abstract.',
            $className
        ));
    }

    /**
     * Asserts that a class is final.
     *
     * Useful to prevent long inheritance chains.
     *
     * @param class-string $className
     */
    final protected static function assertClassIsFinal(string $className): void
    {
        self::assertClassExists($className);

        /** @var class-string $className */
        $reflection = new \ReflectionClass($className);

        self::assertTrue($reflection->isFinal(), \sprintf(
            'Failed asserting that class "%s" is final.',
            $className
        ));
    }

    /**
     * Asserts that a class satisfies a specification.
     *
     * The specification will be invoked with a single argument, the class name, and should return true or false.
     *
     * @param callable(class-string):bool $specification
     * @param class-string                $className
     * @param string                      $message
     */
    final protected static function assertClassSatisfiesSpecification(callable $specification, string $className, string $message = ''): void
    {
        self::assertClassExists($className);

        self::assertTrue($specification($className), \sprintf(
            '' !== $message ? $message : 'Failed asserting that class "%s" satisfies a specification.',
            $className
        ));
    }

    /**
     * Asserts that a class uses a trait.
     *
     * @param class-string $traitName
     * @param class-string $className
     */
    final protected static function assertClassUsesTrait(string $traitName, string $className): void
    {
        self::assertTraitExists($traitName);
        self::assertClassExists($className);

        self::assertContains($traitName, \class_uses($className), \sprintf(
            'Failed asserting that class "%s" uses trait "%s".',
            $className,
            $traitName
        ));
    }

    /**
     * Asserts that an interface exists.
     *
     * @param string $interfaceName
     */
    final protected static function assertInterfaceExists(string $interfaceName): void
    {
        self::assertTrue(\interface_exists($interfaceName), \sprintf(
            'Failed asserting that an interface "%s" exists.',
            $interfaceName
        ));
    }

    /**
     * Asserts that an interface extends a parent interface.
     *
     * @param class-string $parentInterfaceName
     * @param class-string $interfaceName
     */
    final protected static function assertInterfaceExtends(string $parentInterfaceName, string $interfaceName): void
    {
        self::assertInterfaceExists($parentInterfaceName);
        self::assertInterfaceExists($interfaceName);

        /** @var class-string $interfaceName */
        $reflection = new \ReflectionClass($interfaceName);

        self::assertTrue($reflection->isSubclassOf($parentInterfaceName), \sprintf(
            'Failed asserting that interface "%s" extends "%s".',
            $interfaceName,
            $parentInterfaceName
        ));
    }

    /**
     * Asserts that an interface satisfies a specification.
     *
     * The specification will be invoked with a single argument, the class name, and should return true or false.
     *
     * @param callable(class-string):bool $specification
     * @param class-string                $interfaceName
     * @param string                      $message
     */
    final protected static function assertInterfaceSatisfiesSpecification(callable $specification, string $interfaceName, string $message = ''): void
    {
        self::assertInterfaceExists($interfaceName);

        self::assertTrue($specification($interfaceName), \sprintf(
            '' !== $message ? $message : 'Failed asserting that interface "%s" satisfies a specification.',
            $interfaceName
        ));
    }

    /**
     * Asserts that a trait exists.
     *
     * @param string $traitName
     */
    final protected static function assertTraitExists(string $traitName): void
    {
        self::assertTrue(\trait_exists($traitName), \sprintf(
            'Failed asserting that a trait "%s" exists.',
            $traitName
        ));
    }

    /**
     * Asserts that a trait satisfies a specification.
     *
     * The specification will be invoked with a single argument, the class name, and should return true or false.
     *
     * @param callable(class-string):bool $specification
     * @param class-string                $traitName
     * @param string                      $message
     */
    final protected static function assertTraitSatisfiesSpecification(callable $specification, string $traitName, string $message = ''): void
    {
        self::assertTraitExists($traitName);

        self::assertTrue($specification($traitName), \sprintf(
            '' !== $message ? $message : 'Failed asserting that trait "%s" satisfies a specification.',
            $traitName
        ));
    }
}
