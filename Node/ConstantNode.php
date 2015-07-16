<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Profideo\Component\ExpressionLanguage\Node;

use Profideo\Component\ExpressionLanguage\Compiler;

class ConstantNode extends Node
{
    public function __construct($value)
    {
        parent::__construct(
            array(),
            array('value' => $value)
        );
    }

    public function compile(Compiler $compiler)
    {
        $compiler->repr($this->attributes['value']);
    }

    public function evaluate($functions, $values)
    {
        return $this->attributes['value'];
    }
}
