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

use Ergebnis\PhpCsFixer;

$config = PhpCsFixer\Config\Factory::fromRuleSet(new PhpCsFixer\Config\RuleSet\Php74(''), [
    'final_class' => false,
]);

$config->getFinder()->in(__DIR__ . '/test/Fixture');

$config->setCacheFile(__DIR__ . '/.build/php-cs-fixer/.php-cs-fixer.fixture.cache');

return $config;
