<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Profideo\Component\ExpressionLanguage\Tests\Node;

use Profideo\Component\ExpressionLanguage\Node\FunctionNode;
use Profideo\Component\ExpressionLanguage\Node\ConstantNode;
use Profideo\Component\ExpressionLanguage\Node\Node;

class FunctionNodeTest extends AbstractNodeTest
{
    public function getEvaluateData()
    {
        return array(
            array('bar', new FunctionNode('foo', new Node(array(new ConstantNode('bar')))), array(), array('foo' => $this->getCallables())),
        );
    }

    public function getCompileData()
    {
        return array(
            array('foo("bar")', new FunctionNode('foo', new Node(array(new ConstantNode('bar')))), array('foo' => $this->getCallables())),
        );
    }

    protected function getCallables()
    {
        return array(
            'compiler' => function ($arg) {
                return sprintf('foo(%s)', $arg);
            },
            'evaluator' => function ($variables, $arg) {
                return $arg;
            },
        );
    }
}
