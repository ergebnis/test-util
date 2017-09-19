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
     * @param string   $directory
     * @param string   $namespace
     * @param string   $testNamespace
     * @param string[] $excludeClassNames
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

    final protected function assertClassExists(string $className)
    {
        $this->assertTrue(\class_exists($className), \sprintf(
            'Failed to assert that a class "%s" exists.',
            $className
        ));
    }

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

    final protected function assertClassIsAbstractOrFinal(string $className)
    {
        $this->assertClassExists($className);

        $reflection = new \ReflectionClass($className);

        $this->assertTrue($reflection->isAbstract() || $reflection->isFinal(), \sprintf(
            'Failed to assert that class "%s" is abstract or final.',
            $className
        ));
    }

    final protected function assertClassSatisfiesSpecification(callable $specification, string $className, string $message = '')
    {
        $this->assertClassExists($className);

        $this->assertTrue($specification($className), \sprintf(
            '' !== $message ? $message : 'Failed to assert that class "%s" satisfies a specification.',
            $className
        ));
    }

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

    final protected function assertInterfaceExists(string $interfaceName)
    {
        $this->assertTrue(\interface_exists($interfaceName), \sprintf(
            'Failed to assert that an interface "%s" exists.',
            $interfaceName
        ));
    }

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

    final protected function assertInterfaceSatisfiesSpecification(callable $specification, string $interfaceName, string $message = '')
    {
        $this->assertInterfaceExists($interfaceName);

        $this->assertTrue($specification($interfaceName), \sprintf(
            '' !== $message ? $message : 'Failed to assert that interface "%s" satisfies a specification.',
            $interfaceName
        ));
    }

    final protected function assertTraitExists(string $traitName)
    {
        $this->assertTrue(\trait_exists($traitName), \sprintf(
            'Failed to assert that a trait "%s" exists.',
            $traitName
        ));
    }

    final protected function assertTraitSatisfiesSpecification(callable $specification, string $traitName, string $message = '')
    {
        $this->assertTraitExists($traitName);

        $this->assertTrue($specification($traitName), \sprintf(
            '' !== $message ? $message : 'Failed to assert that trait "%s" satisfies a specification.',
            $traitName
        ));
    }
}
