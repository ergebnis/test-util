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
use Zend\File;

trait Helper
{
    final protected function faker(string $locale = 'en_US'): Generator
    {
        static $fakers = [];

        if (false === \class_exists(Generator::class)) {
            $this->triggerMissingPackageError(
                __METHOD__,
                'fzaninotto/faker'
            );
        }

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
        if (false === \class_exists(File\ClassFileLocator::class)) {
            $this->triggerMissingPackageError(
                __METHOD__,
                'zendframework/zend-file'
            );
        }

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

        $classNamesNeitherAbstractNorFinal = \array_filter($classNames, function ($className) {
            $reflection = new \ReflectionClass($className);

            if ($reflection->isAbstract()
                || $reflection->isFinal()
                || $reflection->isInterface()
                || $reflection->isTrait()
            ) {
                return false;
            }

            return true;
        });

        $this->assertEmpty($classNamesNeitherAbstractNorFinal, \sprintf(
            "Failed to assert that the classes\n\n%s\n\nare abstract or final.",
            ' - ' . \implode("\n - ", $classNamesNeitherAbstractNorFinal)
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

    final protected function assertTraitExists(string $traitName)
    {
        $this->assertTrue(\trait_exists($traitName), \sprintf(
            'Failed to assert that a trait "%s" exists.',
            $traitName
        ));
    }

    private function triggerMissingPackageError(string $method, string $package)
    {
        \trigger_error(
            \sprintf(
                'For using the method "%s()", the package "%s" needs to be installed, but it appears that it is not.',
                $method,
                $package
            ),
            E_USER_ERROR
        );
    }
}
