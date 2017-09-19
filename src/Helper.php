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

namespace Localheinz\Test\Util;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework;
use Zend\File;

trait Helper
{
    /**
     * Returns a localized instance of Faker\Generator.
     *
     * Useful for generating fake data in tests.
     *
     * @link https://github.com/fzaninotto/Faker
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
     * @throws \InvalidArgumentException
     */
    final protected function assertClassesAreAbstractOrFinal(string $directory, array $excludeClassNames = [])
    {
        $this->assertClassesSatisfySpecification(
            function (string $className) {
                $reflection = new \ReflectionClass($className);

                return $reflection->isAbstract()
                    || $reflection->isFinal()
                    || $reflection->isInterface()
                    || $reflection->isTrait();
            },
            $directory,
            $excludeClassNames,
            "Failed to assert that the classes\n\n%s\n\nare abstract or final."
        );
    }

    /**
     * Asserts that classes in a directory have matching test classes extending from PHPUnit\Framework\TestCase.
     *
     * @param string   $directory
     * @param string   $namespace
     * @param string   $testNamespace
     * @param string[] $excludeClassNames
     *
     * @throws \InvalidArgumentException
     */
    final protected function assertClassesHaveTests(string $directory, string $namespace, string $testNamespace, array $excludeClassNames = [])
    {
        $namespace = \rtrim($namespace, '\\') . '\\';
        $testNamespace = \rtrim($testNamespace, '\\') . '\\';

        $this->assertClassesSatisfySpecification(
            function (string $className) use ($namespace, $testNamespace) {
                $reflection = new \ReflectionClass($className);

                if ($reflection->isAbstract()
                    || $reflection->isInterface()
                    || $reflection->isTrait()
                    || $reflection->isSubclassOf(Framework\TestCase::class)
                ) {
                    return true;
                }

                $testClassName = \str_replace(
                    $namespace,
                    $testNamespace,
                    $className
                ) . 'Test';

                if (!\class_exists($testClassName)) {
                    return false;
                }

                $testReflection = new \ReflectionClass($testClassName);

                return $testReflection->isSubclassOf(Framework\TestCase::class);
            },
            $directory,
            $excludeClassNames,
            "Failed to assert that the classes\n\n%s\n\nhave tests."
        );
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
     * @param string[] $excludeClassNames
     * @param string   $message
     */
    final protected function assertClassesSatisfySpecification(callable $specification, string $directory, array $excludeClassNames = [], string $message = '')
    {
        if (!\is_dir($directory)) {
            throw new \InvalidArgumentException(\sprintf(
                'Directory "%s" does not exist.',
                $directory
            ));
        }

        \array_walk($excludeClassNames, function ($excludeClassName) {
            if (!\is_string($excludeClassName)) {
                throw new \InvalidArgumentException(\sprintf(
                    'Exclude class names need to be specified as an array of strings, got "%s" instead.',
                    \is_object($excludeClassName) ? \get_class($excludeClassName) : \gettype($excludeClassName)
                ));
            }
        });

        $directory = \realpath($directory);

        $nonExistentExcludeClassNames = \array_filter($excludeClassNames, function (string $excludeClassName) {
            return false === \class_exists($excludeClassName);
        });

        if (0 < \count($nonExistentExcludeClassNames)) {
            throw new \InvalidArgumentException(\sprintf(
                'Exclude class names need to be specified as existing classes, but "%s" does not exist.',
                \implode('", "', $excludeClassNames)
            ));
        }

        $classFileLocator = new File\ClassFileLocator($directory);

        /** @var File\PhpClassFile[] $classFiles */
        $classFiles = \iterator_to_array(
            $classFileLocator,
            false
        );

        $classNames = \array_reduce(
            $classFiles,
            function (array $classNames, File\PhpClassFile $classFile) use ($excludeClassNames) {
                return \array_merge(
                    $classNames,
                    \array_diff(
                        $classFile->getClasses(),
                        $excludeClassNames
                    )
                );
            },
            []
        );

        \sort($classNames);

        $classNamesNotSatisfyingSpecification = \array_filter($classNames, function (string $className) use ($specification) {
            return false === $specification($className);
        });

        $this->assertEmpty($classNamesNotSatisfyingSpecification, \sprintf(
            '' !== $message ? $message : "Failed to assert that the classes\n\n%s\n\nsatisfy a specification.",
            ' - ' . \implode("\n - ", $classNamesNotSatisfyingSpecification)
        ));
    }

    /**
     * Asserts that a class exists.
     *
     * @param string $className
     */
    final protected function assertClassExists(string $className)
    {
        $this->assertTrue(\class_exists($className), \sprintf(
            'Failed to assert that a class "%s" exists.',
            $className
        ));
    }

    /**
     * Asserts that a class extends from a parent class.
     *
     * @param string $parentClassName
     * @param string $className
     */
    final protected function assertClassExtends(string $parentClassName, string $className)
    {
        $this->assertClassExists($parentClassName);
        $this->assertClassExists($className);

        $reflection = new \ReflectionClass($className);

        $this->assertTrue($reflection->isSubclassOf($parentClassName), \sprintf(
            'Failed to assert that class "%s" extends "%s".',
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
    final protected function assertClassImplementsInterface(string $interfaceName, string $className)
    {
        $this->assertInterfaceExists($interfaceName);
        $this->assertClassExists($className);

        $reflection = new \ReflectionClass($className);

        $this->assertTrue($reflection->implementsInterface($interfaceName), \sprintf(
            'Failed to assert that class "%s" implements interface "%s".',
            $className,
            $interfaceName
        ));
    }

    /**
     * Asserts that a class is abstract or final.
     *
     * Useful to prevent long inheritance chains.
     *
     * @param string $className
     */
    final protected function assertClassIsAbstractOrFinal(string $className)
    {
        $this->assertClassExists($className);

        $reflection = new \ReflectionClass($className);

        $this->assertTrue($reflection->isAbstract() || $reflection->isFinal(), \sprintf(
            'Failed to assert that class "%s" is abstract or final.',
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
    final protected function assertClassSatisfiesSpecification(callable $specification, string $className, string $message = '')
    {
        $this->assertClassExists($className);

        $this->assertTrue($specification($className), \sprintf(
            '' !== $message ? $message : 'Failed to assert that class "%s" satisfies a specification.',
            $className
        ));
    }

    /**
     * Asserts that a class uses a trait.
     *
     * @param string $traitName
     * @param string $className
     */
    final protected function assertClassUsesTrait(string $traitName, string $className)
    {
        $this->assertTraitExists($traitName);
        $this->assertClassExists($className);

        $this->assertContains($traitName, \class_uses($className), \sprintf(
            'Failed to assert that class "%s" uses trait "%s".',
            $className,
            $traitName
        ));
    }

    /**
     * Asserts that an interface exists.
     *
     * @param string $interfaceName
     */
    final protected function assertInterfaceExists(string $interfaceName)
    {
        $this->assertTrue(\interface_exists($interfaceName), \sprintf(
            'Failed to assert that an interface "%s" exists.',
            $interfaceName
        ));
    }

    /**
     * Asserts that an interface extends a parent interface.
     *
     * @param string $parentInterfaceName
     * @param string $interfaceName
     */
    final protected function assertInterfaceExtends(string $parentInterfaceName, string $interfaceName)
    {
        $this->assertInterfaceExists($parentInterfaceName);
        $this->assertInterfaceExists($interfaceName);

        $reflection = new \ReflectionClass($interfaceName);

        $this->assertTrue($reflection->isSubclassOf($parentInterfaceName), \sprintf(
            'Failed to assert that interface "%s" extends "%s".',
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
    final protected function assertInterfaceSatisfiesSpecification(callable $specification, string $interfaceName, string $message = '')
    {
        $this->assertInterfaceExists($interfaceName);

        $this->assertTrue($specification($interfaceName), \sprintf(
            '' !== $message ? $message : 'Failed to assert that interface "%s" satisfies a specification.',
            $interfaceName
        ));
    }

    /**
     * Asserts that a trait exists.
     *
     * @param string $traitName
     */
    final protected function assertTraitExists(string $traitName)
    {
        $this->assertTrue(\trait_exists($traitName), \sprintf(
            'Failed to assert that a trait "%s" exists.',
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
    final protected function assertTraitSatisfiesSpecification(callable $specification, string $traitName, string $message = '')
    {
        $this->assertTraitExists($traitName);

        $this->assertTrue($specification($traitName), \sprintf(
            '' !== $message ? $message : 'Failed to assert that trait "%s" satisfies a specification.',
            $traitName
        ));
    }
}
