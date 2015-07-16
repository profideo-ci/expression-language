<?php

namespace Profideo\Component\ExpressionLanguage\Tests;

use Profideo\Component\ExpressionLanguage\Node\ConstantNode;
use Profideo\Component\ExpressionLanguage\ParsedExpression;

class ParsedExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function testSerialization()
    {
        $expression = new ParsedExpression('25', new ConstantNode('25'));

        $serializedExpression = serialize($expression);
        $unserializedExpression = unserialize($serializedExpression);

        $this->assertEquals($expression, $unserializedExpression);
    }
}
