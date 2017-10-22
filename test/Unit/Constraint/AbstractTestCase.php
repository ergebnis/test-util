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

namespace Localheinz\Test\Util\Test\Unit\Constraint;

use Localheinz\Test\Util\Helper;
use PHPUnit\Framework;

abstract class AbstractTestCase extends Framework\TestCase
{
    use Helper;

    final public function testExtendsConstraint()
    {
        $this->assertClassExtends(Framework\Constraint\Constraint::class, $this->className());
    }

    final protected function className(): string
    {
        return \preg_replace(
            '/Test$/',
            '',
            \str_replace(
                'Localheinz\\Test\\Util\\Test\\Unit\\Constraint\\',
                'Localheinz\\Test\\Util\\Constraint\\',
                static::class
            )
        );
    }
}
