<?php

namespace Profideo\Component\ExpressionLanguage\Tests;

use Profideo\Component\ExpressionLanguage\Expression;

class ExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function testSerialization()
    {
        $expression = new Expression('kernel.boot()');

        $serializedExpression = serialize($expression);
        $unserializedExpression = unserialize($serializedExpression);

        $this->assertEquals($expression, $unserializedExpression);
    }
}
