<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Profideo\Component\ExpressionLanguage\Tests\Fixtures;

use Profideo\Component\ExpressionLanguage\ExpressionFunction;
use Profideo\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class TestProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions()
    {
        return array(
            new ExpressionFunction('identity', function ($input) {
                return $input;
            }, function (array $values, $input) {
                return $input;
            }),
        );
    }
}
