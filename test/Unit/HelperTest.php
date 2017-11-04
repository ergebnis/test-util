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
use Localheinz\Test\Util\Test\Fixture;
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

    public function testAssertClassesAreAbstractOrFinalRejectsNonExistentDirectory()
    {
        $directory = __DIR__ . '/../Fixture/NonExistentDirectory';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Directory "%s" does not exist.',
            $directory
        ));

        $this->assertClassesAreAbstractOrFinal($directory);
    }

    public function testAssertClassesAreAbstractOrFinalFailsWhenFoundClassesAreNeitherAbstractNorFinal()
    {
        $directory = __DIR__ . '/../Fixture/ClassesAreAbstractOrFinal/NotAllAbstractOrFinal';
        $classesNeitherAbstractNorFinal = [
            Fixture\ClassesAreAbstractOrFinal\NotAllAbstractOrFinal\AlsoNeitherAbstractNorFinal::class,
            Fixture\ClassesAreAbstractOrFinal\NotAllAbstractOrFinal\NeitherAbstractNorFinal::class,
        ];

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            "Failed asserting that the classes\n\n%s\n\nare abstract or final.",
            ' - ' . \implode("\n - ", $classesNeitherAbstractNorFinal)
        ));

        $this->assertClassesAreAbstractOrFinal($directory);
    }

    public function testAssertClassesAreAbstractOrFinalSucceedsWhenNoClassesHaveBeenFound()
    {
        $directory = __DIR__ . '/../Fixture/ClassesAreAbstractOrFinal/EmptyDirectory';

        $this->assertClassesAreAbstractOrFinal($directory);
    }

    public function testAssertClassesAreAbstractOrFinalSucceedsWhenFoundClassesAreAbstractOrFinal()
    {
        $directory = __DIR__ . '/../Fixture/ClassesAreAbstractOrFinal/AbstractOrFinal';

        $this->assertClassesAreAbstractOrFinal($directory);
    }

    /**
     * @dataProvider providerInvalidExcludeClassyName
     *
     * @param mixed $excludeClassName
     */
    public function testAssertClassesAreAbstractOrFinalWithExcludeClassNamesRejectsInvalidExcludeClassNames($excludeClassName)
    {
        $directory = __DIR__ . '/../Fixture/ClassesAreAbstractOrFinal';
        $excludeClassNames = [
            $excludeClassName,
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Exclude classy names need to be specified as an array of strings, got "%s" instead.',
            \is_object($excludeClassName) ? \get_class($excludeClassName) : \gettype($excludeClassName)
        ));

        $this->assertClassesAreAbstractOrFinal(
            $directory,
            $excludeClassNames
        );
    }

    public function providerInvalidExcludeClassyName(): \Generator
    {
        $classyNames = [
            'array' => [
                'foo',
                'bar',
                'baz',
            ],
            'boolean-false' => false,
            'boolean-true' => true,
            'float' => 3.14,
            'integer' => 9000,
            'null' => null,
            'object' => new \stdClass(),
            'resource' => \fopen(__FILE__, 'rb'),
        ];

        foreach ($classyNames as $key => $classyName) {
            yield $key => [
                $classyName,
            ];
        }
    }

    public function testAssertClassesAreAbstractOrFinalWithExcludeClassNamesFailsWhenFoundClassesAreNeitherAbstractNorFinal()
    {
        $directory = __DIR__ . '/../Fixture/ClassesAreAbstractOrFinal/NotAllAbstractOrFinal';
        $excludeClassyNames = [
            Fixture\ClassesAreAbstractOrFinal\NotAllAbstractOrFinal\AlsoNeitherAbstractNorFinal::class,
        ];

        $classesNeitherAbstractNorFinal = [
            Fixture\ClassesAreAbstractOrFinal\NotAllAbstractOrFinal\NeitherAbstractNorFinal::class,
        ];

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            "Failed asserting that the classes\n\n%s\n\nare abstract or final.",
            ' - ' . \implode("\n - ", $classesNeitherAbstractNorFinal)
        ));

        $this->assertClassesAreAbstractOrFinal(
            $directory,
            $excludeClassyNames
        );
    }

    public function testAssertClassesAreAbstractOrFinalWithExcludeClassNamesSucceedsWhenFoundClassesAreAbstractOrFinal()
    {
        $directory = __DIR__ . '/../Fixture/ClassesAreAbstractOrFinal';
        $excludeClassyNames = [
            Fixture\ClassesAreAbstractOrFinal\NotAllAbstractOrFinal\AlsoNeitherAbstractNorFinal::class,
            Fixture\ClassesAreAbstractOrFinal\NotAllAbstractOrFinal\NeitherAbstractNorFinal::class,
        ];

        $this->assertClassesAreAbstractOrFinal(
            $directory,
            $excludeClassyNames
        );
    }

    public function testAssertClassesAreAbstractOrFinalWithExcludeClassNamesIgnoresNonExistentExcludeClassNames()
    {
        $directory = __DIR__ . '/../Fixture/ClassesAreAbstractOrFinal';
        $excludeClassyNames = [
            Fixture\ClassesAreAbstractOrFinal\NotAllAbstractOrFinal\AlsoNeitherAbstractNorFinal::class,
            Fixture\ClassesAreAbstractOrFinal\NotAllAbstractOrFinal\NeitherAbstractNorFinal::class,
            __NAMESPACE__ . '\\NonExistentClass',
        ];

        $this->assertClassesAreAbstractOrFinal(
            $directory,
            $excludeClassyNames
        );
    }

    public function testAssertClassesHaveTestsRejectsNonExistentDirectory()
    {
        $directory = __DIR__ . '/../Fixture/NonExistentDirectory';
        $namespace = '';
        $testNamespace = '';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Directory "%s" does not exist.',
            $directory
        ));

        $this->assertClassesHaveTests(
            $directory,
            $namespace,
            $testNamespace
        );
    }

    public function testAssertClassesHaveTestsFailsWhenFoundClassesDoNotHaveTests()
    {
        $directory = __DIR__ . '/../Fixture/ClassesHaveTests/WithoutTests';
        $namespace = 'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\WithoutTests\\';
        $testNamespace = 'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\WithoutTests\\Test\\';

        $classesWithoutTests = [
            Fixture\ClassesHaveTests\WithoutTests\ExampleClass::class,
        ];

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            "Failed asserting that the classes\n\n%s\n\nhave tests. Expected corresponding test classes\n\n%s\n\nbut could not find them.",
            \implode("\n", \array_map(function (string $className) {
                return \sprintf(
                    ' - %s',
                    $className
                );
            }, $classesWithoutTests)),
            \implode("\n", \array_map(function (string $className) use ($namespace, $testNamespace) {
                $testClassName = \str_replace(
                        $namespace,
                        $testNamespace,
                        $className
                    ) . 'Test';

                return \sprintf(
                    ' - %s',
                    $testClassName
                );
            }, $classesWithoutTests))
        ));

        $this->assertClassesHaveTests(
            $directory,
            $namespace,
            $testNamespace
        );
    }

    public function testAssertClassesHaveTestsSucceedsWhenNoClassesHaveBeenFound()
    {
        $directory = __DIR__ . '/../Fixture/ClassesHaveTests/EmptyDirectory';
        $namespace = 'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\EmptyDirectory\\';
        $testNamespace = 'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\EmptyDirectory\\Test\\';

        $this->assertClassesHaveTests(
            $directory,
            $namespace,
            $testNamespace
        );
    }

    public function testAssertClassesHaveTestsSucceedsWhenFoundClassesHaveTests()
    {
        $directory = __DIR__ . '/../Fixture/ClassesHaveTests/WithTests';
        $namespace = 'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\WithTests\\';
        $testNamespace = 'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\WithTests\\Test\\';

        $this->assertClassesHaveTests(
            $directory,
            $namespace,
            $testNamespace
        );
    }

    /**
     * @dataProvider providerNamespaceAndTestNamespace
     *
     * @param string $namespace
     * @param string $testNamespace
     */
    public function testAssertClassesHaveTestsWorksWithAndWithoutTrailingSlash(string $namespace, string $testNamespace)
    {
        $directory = __DIR__ . '/../Fixture/ClassesHaveTests/WithTests';

        $this->assertClassesHaveTests(
            $directory,
            $namespace,
            $testNamespace
        );
    }

    public function providerNamespaceAndTestNamespace(): \Generator
    {
        $namespaces = [
            'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\WithTests',
            'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\WithTests\\',
        ];

        $testNamespaces = [
            'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\WithTests\\Test',
            'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\WithTests\\Test\\',
        ];

        foreach ($namespaces as $namespace) {
            foreach ($testNamespaces as $testNamespace) {
                yield [
                    $namespace,
                    $testNamespace,
                ];
            }
        }
    }

    /**
     * @dataProvider providerInvalidExcludeClassyName
     *
     * @param mixed $excludeClassyName
     */
    public function testAssertClassesHaveTestsWithExcludeClassNamesRejectsInvalidExcludeClassNames($excludeClassyName)
    {
        $directory = __DIR__ . '/../Fixture/ClassesHaveTests/NotAllClassesHaveTests';
        $namespace = 'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\NotAllClassesHaveTests\\';
        $testNamespace = 'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\NotAllClassesHaveTests\\Test\\';
        $excludeClassyNames = [
            $excludeClassyName,
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Exclude classy names need to be specified as an array of strings, got "%s" instead.',
            \is_object($excludeClassyName) ? \get_class($excludeClassyName) : \gettype($excludeClassyName)
        ));

        $this->assertClassesHaveTests(
            $directory,
            $namespace,
            $testNamespace,
            $excludeClassyNames
        );
    }

    public function testAssertClassesHaveTestsWithExcludeClassNamesFailsWhenFoundClassesDoNotHaveTests()
    {
        $directory = __DIR__ . '/../Fixture/ClassesHaveTests/NotAllClassesHaveTests';
        $namespace = 'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\NotAllClassesHaveTests\\';
        $testNamespace = 'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\NotAllClassesHaveTests\\Test\\';
        $excludeClassyNames = [
            Fixture\ClassesHaveTests\NotAllClassesHaveTests\AnotherExampleClass::class,
        ];

        $classesWithoutTests = [
            Fixture\ClassesHaveTests\NotAllClassesHaveTests\OneMoreExampleClass::class,
        ];

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            "Failed asserting that the classes\n\n%s\n\nhave tests.",
            ' - ' . \implode("\n - ", $classesWithoutTests)
        ));

        $this->assertClassesHaveTests(
            $directory,
            $namespace,
            $testNamespace,
            $excludeClassyNames
        );
    }

    public function testAssertClassesHaveTestsWithExcludeClassNamesSucceedsWhenFoundClassesHaveTests()
    {
        $directory = __DIR__ . '/../Fixture/ClassesHaveTests/NotAllClassesHaveTests';
        $namespace = 'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\NotAllClassesHaveTests\\';
        $testNamespace = 'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\NotAllClassesHaveTests\\Test\\';
        $excludeClassyNames = [
            Fixture\ClassesHaveTests\NotAllClassesHaveTests\AnotherExampleClass::class,
            Fixture\ClassesHaveTests\NotAllClassesHaveTests\OneMoreExampleClass::class,
        ];

        $this->assertClassesHaveTests(
            $directory,
            $namespace,
            $testNamespace,
            $excludeClassyNames
        );
    }

    public function testAssertClassesHaveTestsWithExcludeClassNamesIgnoresNonExistentExcludeClassNames()
    {
        $directory = __DIR__ . '/../Fixture/ClassesHaveTests/NotAllClassesHaveTests';
        $namespace = 'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\NotAllClassesHaveTests\\';
        $testNamespace = 'Localheinz\\Test\\Util\\Test\\Fixture\\ClassesHaveTests\\NotAllClassesHaveTests\\Test\\';
        $excludeClassyNames = [
            Fixture\ClassesHaveTests\NotAllClassesHaveTests\AnotherExampleClass::class,
            Fixture\ClassesHaveTests\NotAllClassesHaveTests\OneMoreExampleClass::class,
            __NAMESPACE__ . '\\NonExistentClass',
        ];

        $this->assertClassesHaveTests(
            $directory,
            $namespace,
            $testNamespace,
            $excludeClassyNames
        );
    }

    public function testAssertClassyConstructsSatisfySpecificationRejectsNonExistentDirectory()
    {
        $directory = __DIR__ . '/../Fixture/NonExistentDirectory';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Directory "%s" does not exist.',
            $directory
        ));

        $this->assertClassyConstructsSatisfySpecification(
            function (string $classyName) {
                return false;
            },
            $directory
        );
    }

    public function testAssertClassyConstructsSatisfySpecificationFailsWhenFoundClassesDoNotSatisfySpecification()
    {
        $directory = __DIR__ . '/../Fixture/ClassyConstructsSatisfySpecification';
        $classesNotSatisfyingSpecification = [
            Fixture\ClassyConstructsSatisfySpecification\AnotherExampleClass::class,
            Fixture\ClassyConstructsSatisfySpecification\ExampleClass::class,
        ];

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            "Failed asserting that the classy constructs\n\n%s\n\nsatisfy a specification.",
            ' - ' . \implode("\n - ", $classesNotSatisfyingSpecification)
        ));

        $this->assertClassyConstructsSatisfySpecification(
            function (string $className) {
                return false;
            },
            $directory
        );
    }

    public function testAssertClassyConstructsSatisfySpecificationSucceedsWhenNoClassesHaveBeenFound()
    {
        $directory = __DIR__ . '/../Fixture/ClassyConstructsSatisfySpecification/EmptyDirectory';

        $this->assertClassyConstructsSatisfySpecification(
            function (string $className) {
                return false;
            },
            $directory
        );
    }

    public function testAssertClassyConstructsSatisfySpecificationSucceedsWhenFoundClassesSatisfySpecification()
    {
        $directory = __DIR__ . '/../Fixture/ClassyConstructsSatisfySpecification';

        $this->assertClassyConstructsSatisfySpecification(
            function (string $className) {
                return true;
            },
            $directory
        );
    }

    /**
     * @dataProvider providerInvalidExcludeClassyName
     *
     * @param mixed $excludeClassyName
     */
    public function testAssertClassyConstructsSatisfySpecificationWithExcludeClassNamesRejectsInvalidExcludeClassNames($excludeClassyName)
    {
        $directory = __DIR__ . '/../Fixture/ClassyConstructsSatisfySpecification';
        $excludeClassyNames = [
            $excludeClassyName,
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Exclude classy names need to be specified as an array of strings, got "%s" instead.',
            \is_object($excludeClassyName) ? \get_class($excludeClassyName) : \gettype($excludeClassyName)
        ));

        $this->assertClassyConstructsSatisfySpecification(
            function (string $classyName) {
                return true;
            },
            $directory,
            $excludeClassyNames
        );
    }

    public function testAssertClassyConstructsSatisfySpecificationWithExcludeClassyNamesFailsWhenFoundClassyConstructsDoNotSatisfySpecification()
    {
        $directory = __DIR__ . '/../Fixture/ClassyConstructsSatisfySpecification';
        $excludeClassyNames = [
            Fixture\ClassyConstructsSatisfySpecification\AnotherExampleClass::class,
        ];

        $classesNotSatisfyingSpecification = [
            Fixture\ClassyConstructsSatisfySpecification\ExampleClass::class,
        ];

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            "Failed asserting that the classy constructs\n\n%s\n\nsatisfy a specification.",
            ' - ' . \implode("\n - ", $classesNotSatisfyingSpecification)
        ));

        $this->assertClassyConstructsSatisfySpecification(
            function (string $classyName) {
                return false;
            },
            $directory,
            $excludeClassyNames
        );
    }

    public function testAssertClassyConstructsSpecificationWithExcludeClassNamesSucceedsWhenFoundClassesSatisfySpecification()
    {
        $directory = __DIR__ . '/../Fixture/ClassyConstructsSatisfySpecification';
        $excludeClassyNames = [
            Fixture\ClassyConstructsSatisfySpecification\AnotherExampleClass::class,
        ];

        $this->assertClassyConstructsSatisfySpecification(
            function (string $classyName) {
                return Fixture\ClassyConstructsSatisfySpecification\ExampleClass::class === $classyName;
            },
            $directory,
            $excludeClassyNames
        );
    }

    public function testAssertClassyConstructsSpecificationWithExcludeClassNamesIgnoresNonExistentExcludeClassNames()
    {
        $directory = __DIR__ . '/../Fixture/ClassyConstructsSatisfySpecification';
        $excludeClassyNames = [
            Fixture\ClassyConstructsSatisfySpecification\AnotherExampleClass::class,
            __NAMESPACE__ . '\\NonExistentClass',
        ];

        $this->assertClassyConstructsSatisfySpecification(
            function (string $classyName) {
                return Fixture\ClassyConstructsSatisfySpecification\ExampleClass::class === $classyName;
            },
            $directory,
            $excludeClassyNames
        );
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
            'Failed asserting that a class "%s" exists.',
            $className
        ));

        $this->assertClassExists($className);
    }

    public function providerNotClass(): \Generator
    {
        $classyNames = [
            'class-non-existent' => __NAMESPACE__ . '\NonExistentClass',
            'interface' => Fixture\NotClass\ExampleInterface::class,
            'trait' => Fixture\NotClass\ExampleTrait::class,
        ];

        foreach ($classyNames as $key => $classyName) {
            yield $key => [
                $classyName,
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
            'Failed asserting that a class "%s" exists.',
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
            'Failed asserting that a class "%s" exists.',
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
            'Failed asserting that class "%s" extends "%s".',
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
            'Failed asserting that an interface "%s" exists.',
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
            'Failed asserting that a class "%s" exists.',
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
            'Failed asserting that class "%s" implements interface "%s".',
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
    public function testAssertClassIsAbstractFailsWhenClassIsNotClass(string $className)
    {
        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed asserting that a class "%s" exists.',
            $className
        ));

        $this->assertClassIsAbstract($className);
    }

    public function testAssertClassIsAbstractFailsWhenClassIsNotAbstract()
    {
        $className = Fixture\ClassIsAbstract\ConcreteClass::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed asserting that class "%s" is abstract.',
            $className
        ));

        $this->assertClassIsAbstract($className);
    }

    public function testAssertClassIsAbstractSucceedsWhenClassIsAbstract()
    {
        $className = Fixture\ClassIsAbstract\AbstractClass::class;

        $this->assertClassIsAbstract($className);
    }

    /**
     * @dataProvider providerNotClass
     *
     * @param string $className
     */
    public function testAssertClassIsFinalFailsWhenClassIsNotClass(string $className)
    {
        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed asserting that a class "%s" exists.',
            $className
        ));

        $this->assertClassIsFinal($className);
    }

    public function testAssertClassIsFinalFailsWhenClassIsNotFinal()
    {
        $className = Fixture\ClassIsFinal\NeitherAbstractNorFinalClass::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed asserting that class "%s" is final.',
            $className
        ));

        $this->assertClassIsFinal($className);
    }

    public function testAssertClassIsFinalSucceedsWhenClassIsFinal()
    {
        $className = Fixture\ClassIsFinal\FinalClass::class;

        $this->assertClassIsFinal($className);
    }

    /**
     * @dataProvider providerNotClass
     *
     * @param string $className
     */
    public function testAssertClassSatisfiesSpecificationFailsWhenClassIsNotAClass(string $className)
    {
        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed asserting that a class "%s" exists.',
            $className
        ));

        $this->assertClassSatisfiesSpecification(
            function () {
                return true;
            },
            $className
        );
    }

    public function testAssertClassSatisfiesSpecificationFailsWhenSpecificationReturnsFalse()
    {
        $className = Fixture\ClassSatisfiesSpecification\ExampleClass::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed asserting that class "%s" satisfies a specification.',
            $className
        ));

        $this->assertClassSatisfiesSpecification(
            function () {
                return false;
            },
            $className
        );
    }

    public function testAssertClassSatisfiesSpecificationFailsWhenSpecificationReturnsFalseAndUsesMessage()
    {
        $className = Fixture\ClassSatisfiesSpecification\ExampleClass::class;
        $message = 'Looks like "%s" does not satisfy our requirements right now';

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            $message,
            $className
        ));

        $this->assertClassSatisfiesSpecification(
            function () {
                return false;
            },
            $className,
            $message
        );
    }

    public function testAssertClassSatisfiesSpecificationSucceedsWhenSpecificationReturnsTrue()
    {
        $className = Fixture\ClassSatisfiesSpecification\ExampleClass::class;

        $this->assertClassSatisfiesSpecification(
            function () {
                return true;
            },
            $className
        );
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
            'Failed asserting that a trait "%s" exists.',
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
            'Failed asserting that a class "%s" exists.',
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
            'Failed asserting that class "%s" uses trait "%s".',
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
            'Failed asserting that an interface "%s" exists.',
            $interfaceName
        ));

        $this->assertInterfaceExists($interfaceName);
    }

    public function providerNotInterface(): \Generator
    {
        $interfaceNames = [
            'class' => Fixture\NotInterface\ExampleClass::class,
            'interface-non-existent' => __NAMESPACE__ . '\NonExistentInterface',
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
            'Failed asserting that an interface "%s" exists.',
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
            'Failed asserting that an interface "%s" exists.',
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
            'Failed asserting that interface "%s" extends "%s".',
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
     * @dataProvider providerNotInterface
     *
     * @param string $interfaceName
     */
    public function testAssertInterfaceSatisfiesSpecificationFailsWhenInterfaceIsNotAInterface(string $interfaceName)
    {
        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed asserting that an interface "%s" exists.',
            $interfaceName
        ));

        $this->assertInterfaceSatisfiesSpecification(
            function () {
                return true;
            },
            $interfaceName
        );
    }

    public function testAssertInterfaceSatisfiesSpecificationFailsWhenSpecificationReturnsFalse()
    {
        $interfaceName = Fixture\InterfaceSatisfiesSpecification\ExampleInterface::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed asserting that interface "%s" satisfies a specification.',
            $interfaceName
        ));

        $this->assertInterfaceSatisfiesSpecification(
            function () {
                return false;
            },
            $interfaceName
        );
    }

    public function testAssertInterfaceSatisfiesSpecificationFailsWhenSpecificationReturnsFalseAndUsesMessage()
    {
        $interfaceName = Fixture\InterfaceSatisfiesSpecification\ExampleInterface::class;
        $message = 'Looks like "%s" does not satisfy our requirements right now';

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            $message,
            $interfaceName
        ));

        $this->assertInterfaceSatisfiesSpecification(
            function () {
                return false;
            },
            $interfaceName,
            $message
        );
    }

    public function testAssertInterfaceSatisfiesSpecificationSucceedsWhenSpecificationReturnsTrue()
    {
        $interfaceName = Fixture\InterfaceSatisfiesSpecification\ExampleInterface::class;

        $this->assertInterfaceSatisfiesSpecification(
            function () {
                return true;
            },
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
            'Failed asserting that a trait "%s" exists.',
            $traitName
        ));

        $this->assertTraitExists($traitName);
    }

    public function providerNotTrait(): \Generator
    {
        $traitNames = [
            'class' => Fixture\NotTrait\ExampleClass::class,
            'interface' => Fixture\NotTrait\ExampleInterface::class,
            'trait-non-existent' => __NAMESPACE__ . '\NonExistentTrait',
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

    /**
     * @dataProvider providerNotTrait
     *
     * @param string $traitName
     */
    public function testAssertTraitSatisfiesSpecificationFailsWhenTraitIsNotATrait(string $traitName)
    {
        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed asserting that a trait "%s" exists.',
            $traitName
        ));

        $this->assertTraitSatisfiesSpecification(
            function () {
                return true;
            },
            $traitName
        );
    }

    public function testAssertTraitSatisfiesSpecificationFailsWhenSpecificationReturnsFalse()
    {
        $traitName = Fixture\TraitSatisfiesSpecification\ExampleTrait::class;

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            'Failed asserting that trait "%s" satisfies a specification.',
            $traitName
        ));

        $this->assertTraitSatisfiesSpecification(
            function () {
                return false;
            },
            $traitName
        );
    }

    public function testAssertTraitSatisfiesSpecificationFailsWhenSpecificationReturnsFalseAndUsesMessage()
    {
        $traitName = Fixture\TraitSatisfiesSpecification\ExampleTrait::class;
        $message = 'Looks like "%s" does not satisfy our requirements right now';

        $this->expectException(Framework\AssertionFailedError::class);
        $this->expectExceptionMessage(\sprintf(
            $message,
            $traitName
        ));

        $this->assertTraitSatisfiesSpecification(
            function () {
                return false;
            },
            $traitName,
            $message
        );
    }

    public function testAssertTraitSatisfiesSpecificationSucceedsWhenSpecificationReturnsTrue()
    {
        $traitName = Fixture\TraitSatisfiesSpecification\ExampleTrait::class;

        $this->assertTraitSatisfiesSpecification(
            function () {
                return true;
            },
            $traitName
        );
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
