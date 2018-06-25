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

namespace Localheinz\Test\Util;

use Faker\Factory;
use Faker\Generator;
use Localheinz\Classy;
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
    final protected function faker(string $locale = 'en_US'): Generator
    {
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
     * @param string   $directory
     * @param string[] $excludeClassNames
     *
     * @throws Exception\NonExistentDirectory
     * @throws Exception\InvalidExcludeClassName
     * @throws Exception\NonExistentExcludeClass
     * @throws Classy\Exception\MultipleDefinitionsFound
     */
    final protected function assertClassesAreAbstractOrFinal(string $directory, array $excludeClassNames = []): void
    {
        $this->assertClassyConstructsSatisfySpecification(
            function (string $className): bool {
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
     * @param string   $directory
     * @param string   $namespace
     * @param string   $testNamespace
     * @param string[] $excludeClassyNames
     *
     * @throws Exception\NonExistentDirectory
     * @throws Exception\InvalidExcludeClassName
     * @throws Exception\NonExistentExcludeClass
     * @throws Classy\Exception\MultipleDefinitionsFound
     */
    final protected function assertClassesHaveTests(string $directory, string $namespace, string $testNamespace, array $excludeClassyNames = []): void
    {
        if (!\is_dir($directory)) {
            throw Exception\NonExistentDirectory::fromDirectory($directory);
        }

        \array_walk($excludeClassyNames, function ($excludeClassyName): void {
            if (!\is_string($excludeClassyName)) {
                throw Exception\InvalidExcludeClassName::fromClassName($excludeClassyName);
            }

            if (!\class_exists($excludeClassyName)) {
                throw Exception\NonExistentExcludeClass::fromClassName($excludeClassyName);
            }
        });

        $constructs = Classy\Constructs::fromDirectory($directory);

        $classyNames = \array_diff(
            \array_map(function (Classy\Construct $construct): string {
                return $construct->name();
            }, $constructs),
            $excludeClassyNames
        );

        $namespace = \rtrim($namespace, '\\') . '\\';
        $testNamespace = \rtrim($testNamespace, '\\') . '\\';

        $testClassNameFrom = function (string $className) use ($namespace, $testNamespace): string {
            return \str_replace(
                $namespace,
                $testNamespace,
                $className
            ) . 'Test';
        };

        $classesWithoutTests = \array_filter($classyNames, function (string $className) use ($testClassNameFrom) {
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
                $testReflection = new \ReflectionClass($testClassName);

                if ($testReflection->isSubclassOf(Framework\TestCase::class) && $testReflection->isInstantiable()) {
                    return false;
                }
            }

            return true;
        });

        $this->assertEmpty($classesWithoutTests, \sprintf(
            "Failed asserting that the classes\n\n%s\n\nhave tests. Expected corresponding test classes\n\n%s\n\nextending from \"%s\" but could not find them.",
            \implode("\n", \array_map(function (string $className) {
                return \sprintf(
                    ' - %s',
                    $className
                );
            }, $classesWithoutTests)),
            \implode("\n", \array_map(function (string $className) use ($testClassNameFrom) {
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
     * @param callable $specification
     * @param string   $directory
     * @param string[] $excludeClassyNames
     * @param string   $message
     *
     * @throws Exception\NonExistentDirectory
     * @throws Exception\InvalidExcludeClassName
     * @throws Exception\NonExistentExcludeClass
     * @throws Classy\Exception\MultipleDefinitionsFound
     */
    final protected function assertClassyConstructsSatisfySpecification(callable $specification, string $directory, array $excludeClassyNames = [], string $message = ''): void
    {
        if (!\is_dir($directory)) {
            throw Exception\NonExistentDirectory::fromDirectory($directory);
        }

        \array_walk($excludeClassyNames, function ($excludeClassyName) {
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

        $classyNamesNotSatisfyingSpecification = \array_filter($classyNames, function (string $className) use ($specification) {
            return false === $specification($className);
        });

        $this->assertEmpty($classyNamesNotSatisfyingSpecification, \sprintf(
            '' !== $message ? $message : "Failed asserting that the classy constructs\n\n%s\n\nsatisfy a specification.",
            ' - ' . \implode("\n - ", $classyNamesNotSatisfyingSpecification)
        ));
    }

    /**
     * Asserts that a class exists.
     *
     * @param string $className
     */
    final protected function assertClassExists(string $className): void
    {
        $this->assertTrue(\class_exists($className), \sprintf(
            'Failed asserting that a class "%s" exists.',
            $className
        ));
    }

    /**
     * Asserts that a class extends from a parent class.
     *
     * @param string $parentClassName
     * @param string $className
     */
    final protected function assertClassExtends(string $parentClassName, string $className): void
    {
        $this->assertClassExists($parentClassName);
        $this->assertClassExists($className);

        $reflection = new \ReflectionClass($className);

        $this->assertTrue($reflection->isSubclassOf($parentClassName), \sprintf(
            'Failed asserting that class "%s" extends "%s".',
            $className,
            $parentClassName
        ));
    }

    /**
     * Asserts that a class implements an interface.
     *
     * @param string $interfaceName
     * @param string $className
     */
    final protected function assertClassImplementsInterface(string $interfaceName, string $className): void
    {
        $this->assertInterfaceExists($interfaceName);
        $this->assertClassExists($className);

        $reflection = new \ReflectionClass($className);

        $this->assertTrue($reflection->implementsInterface($interfaceName), \sprintf(
            'Failed asserting that class "%s" implements interface "%s".',
            $className,
            $interfaceName
        ));
    }

    /**
     * Asserts that a class is abstract.
     *
     * @param string $className
     */
    final protected function assertClassIsAbstract(string $className): void
    {
        $this->assertClassExists($className);

        $reflection = new \ReflectionClass($className);

        $this->assertTrue($reflection->isAbstract(), \sprintf(
            'Failed asserting that class "%s" is abstract.',
            $className
        ));
    }

    /**
     * Asserts that a class is final.
     *
     * Useful to prevent long inheritance chains.
     *
     * @param string $className
     */
    final protected function assertClassIsFinal(string $className): void
    {
        $this->assertClassExists($className);

        $reflection = new \ReflectionClass($className);

        $this->assertTrue($reflection->isFinal(), \sprintf(
            'Failed asserting that class "%s" is final.',
            $className
        ));
    }

    /**
     * Asserts that a class satisfies a specification.
     *
     * The specification will be invoked with a single argument, the class name, and should return true or false.
     *
     * @param callable $specification
     * @param string   $className
     * @param string   $message
     */
    final protected function assertClassSatisfiesSpecification(callable $specification, string $className, string $message = ''): void
    {
        $this->assertClassExists($className);

        $this->assertTrue($specification($className), \sprintf(
            '' !== $message ? $message : 'Failed asserting that class "%s" satisfies a specification.',
            $className
        ));
    }

    /**
     * Asserts that a class uses a trait.
     *
     * @param string $traitName
     * @param string $className
     */
    final protected function assertClassUsesTrait(string $traitName, string $className): void
    {
        $this->assertTraitExists($traitName);
        $this->assertClassExists($className);

        $this->assertContains($traitName, \class_uses($className), \sprintf(
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
    final protected function assertInterfaceExists(string $interfaceName): void
    {
        $this->assertTrue(\interface_exists($interfaceName), \sprintf(
            'Failed asserting that an interface "%s" exists.',
            $interfaceName
        ));
    }

    /**
     * Asserts that an interface extends a parent interface.
     *
     * @param string $parentInterfaceName
     * @param string $interfaceName
     */
    final protected function assertInterfaceExtends(string $parentInterfaceName, string $interfaceName): void
    {
        $this->assertInterfaceExists($parentInterfaceName);
        $this->assertInterfaceExists($interfaceName);

        $reflection = new \ReflectionClass($interfaceName);

        $this->assertTrue($reflection->isSubclassOf($parentInterfaceName), \sprintf(
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
     * @param callable $specification
     * @param string   $interfaceName
     * @param string   $message
     */
    final protected function assertInterfaceSatisfiesSpecification(callable $specification, string $interfaceName, string $message = ''): void
    {
        $this->assertInterfaceExists($interfaceName);

        $this->assertTrue($specification($interfaceName), \sprintf(
            '' !== $message ? $message : 'Failed asserting that interface "%s" satisfies a specification.',
            $interfaceName
        ));
    }

    /**
     * Asserts that a trait exists.
     *
     * @param string $traitName
     */
    final protected function assertTraitExists(string $traitName): void
    {
        $this->assertTrue(\trait_exists($traitName), \sprintf(
            'Failed asserting that a trait "%s" exists.',
            $traitName
        ));
    }

    /**
     * Asserts that a trait satisfies a specification.
     *
     * The specification will be invoked with a single argument, the class name, and should return true or false.
     *
     * @param callable $specification
     * @param string   $traitName
     * @param string   $message
     */
    final protected function assertTraitSatisfiesSpecification(callable $specification, string $traitName, string $message = ''): void
    {
        $this->assertTraitExists($traitName);

        $this->assertTrue($specification($traitName), \sprintf(
            '' !== $message ? $message : 'Failed asserting that trait "%s" satisfies a specification.',
            $traitName
        ));
    }
}
