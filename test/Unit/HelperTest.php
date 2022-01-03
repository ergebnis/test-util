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

namespace Ergebnis\Test\Util\Test\Unit;

use Ergebnis\Test\Util\Helper;
use Faker\Factory;
use Faker\Generator;
use Faker\Provider;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Test\Util\Helper
 */
final class HelperTest extends Framework\TestCase
{
    use Helper;

    public function testHelperMethodsAreFinalProtectedAndStatic(): void
    {
        /** @var class-string $className */
        $className = Helper::class;

        $reflection = new \ReflectionClass($className);

        $methods = $reflection->getMethods();

        $methodsNeitherFinalNorProtected = \array_filter($methods, static function (\ReflectionMethod $method): bool {
            return !$method->isFinal() || !$method->isProtected() || !$method->isStatic();
        });

        self::assertEmpty($methodsNeitherFinalNorProtected, \sprintf(
            "Failed asserting that the methods \n\n%s\n\nare final and protected.",
            \implode("\n", \array_map(static function (\ReflectionMethod $method) use ($className): string {
                return \sprintf(
                    ' - %s::%s()',
                    $className,
                    $method->getName(),
                );
            }, $methodsNeitherFinalNorProtected)),
        ));
    }

    public function testFakerWithoutLocaleReturnsFakerWithDefaultLocale(): void
    {
        $faker = self::faker();

        $this->assertHasOnlyProvidersWithLocale(Factory::DEFAULT_LOCALE, $faker);
    }

    /**
     * @dataProvider providerLocale
     */
    public function testFakerWithLocaleReturnsFakerWithSpecifiedLocale(string $locale): void
    {
        $faker = self::faker($locale);

        $this->assertHasOnlyProvidersWithLocale($locale, $faker);
    }

    /**
     * @dataProvider providerLocale
     */
    public function testFakerReturnsSameFaker(string $locale): void
    {
        $faker = self::faker($locale);

        self::assertSame($faker, self::faker($locale));
    }

    /**
     * @return \Generator<string, array<string>>
     */
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

    private function assertHasOnlyProvidersWithLocale(string $locale, Generator $faker): void
    {
        /** @var Provider\Base[] $providers */
        $providers = $faker->getProviders();

        $providerClasses = \array_map(static function (Provider\Base $provider): string {
            return \get_class($provider);
        }, $providers);

        $providerLocales = \array_reduce(
            $providerClasses,
            static function (array $providerLocales, string $providerClass): array {
                if (0 === \preg_match('/^Faker\\\\Provider\\\\(?P<locale>[a-z]{2}_[A-Z]{2})\\\\/', $providerClass, $matches)) {
                    return $providerLocales;
                }

                $providerLocales[] = $matches['locale'];

                return $providerLocales;
            },
            [],
        );

        $locales = \array_values(\array_unique($providerLocales));

        $expected = [
            $locale,
        ];

        self::assertEquals($expected, $locales);
    }
}
