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

class BinaryNode extends Node
{
    private static $operators = array(
        '~' => '.',
        'and' => '&&',
        'or' => '||',
    );

    private static $functions = array(
        '**' => 'pow',
        '..' => 'range',
        'in' => 'in_array',
        'not in' => '!in_array',
    );

    public function __construct($operator, Node $left, Node $right)
    {
        parent::__construct(
            array('left' => $left, 'right' => $right),
            array('operator' => $operator)
        );
    }

    public function compile(Compiler $compiler)
    {
        $operator = $this->attributes['operator'];

        if ('matches' == $operator) {
            $compiler
                ->raw('preg_match(')
                ->compile($this->nodes['right'])
                ->raw(', ')
                ->compile($this->nodes['left'])
                ->raw(')')
            ;

            return;
        }

        if (isset(self::$functions[$operator])) {
            $compiler
                ->raw(sprintf('%s(', self::$functions[$operator]))
                ->compile($this->nodes['left'])
                ->raw(', ')
                ->compile($this->nodes['right'])
                ->raw(')')
            ;

            return;
        }

        if (isset(self::$operators[$operator])) {
            $operator = self::$operators[$operator];
        }

        $compiler
            ->raw('(')
            ->compile($this->nodes['left'])
            ->raw(' ')
            ->raw($operator)
            ->raw(' ')
            ->compile($this->nodes['right'])
            ->raw(')')
        ;
    }

    public function evaluate($functions, $values)
    {
        $operator = $this->attributes['operator'];
        $left = $this->nodes['left']->evaluate($functions, $values);

        if (isset(self::$functions[$operator])) {
            $right = $this->nodes['right']->evaluate($functions, $values);

            if ('not in' === $operator) {
                return !in_array($left, $right);
            }
            $f = self::$functions[$operator];

            return $f($left, $right);
        }

        switch ($operator) {
            case 'or':
            case '||':
                return $left || $this->nodes['right']->evaluate($functions, $values);
            case 'and':
            case '&&':
                return $left && $this->nodes['right']->evaluate($functions, $values);
        }

        $right = $this->nodes['right']->evaluate($functions, $values);

        switch ($operator) {
            case '|':
                return $left | $right;
            case '^':
                return $left ^ $right;
            case '&':
                return $left & $right;
            case '=':
            case '==':
                return $left == $right && (is_numeric($left) && is_numeric($right) || gettype($left) === gettype($right));
            case '===':
                return $left === $right;
            case '<>':
            case '!=':
                return !($left == $right && (is_numeric($left) && is_numeric($right) || gettype($left) === gettype($right)));
            case '!==':
                return $left !== $right;
            case '<':
                if ('#NC' === $left || '#NC' === $right) {
                    return false;
                } elseif ('#NA' === $left || '#NA' === $right) {
                    return false;
                } elseif ('#SO' === $left || '#SO' === $right) {
                    return false;
                }

                return $left < $right && is_numeric($left) && is_numeric($right);
            case '>':
                if ('#NC' === $left || '#NC' === $right) {
                    return false;
                } elseif ('#NA' === $left || '#NA' === $right) {
                    return false;
                } elseif ('#SO' === $left || '#SO' === $right) {
                    return false;
                }

                return $left > $right && is_numeric($left) && is_numeric($right);
            case '>=':
                if ('#NC' === $left || '#NC' === $right) {
                    return false;
                } elseif ('#NA' === $left || '#NA' === $right) {
                    return false;
                } elseif ('#SO' === $left || '#SO' === $right) {
                    return false;
                }

                return $left >= $right && is_numeric($left) && is_numeric($right);
            case '<=':
                if ('#NC' === $left || '#NC' === $right) {
                    return false;
                } elseif ('#NA' === $left || '#NA' === $right) {
                    return false;
                } elseif ('#SO' === $left || '#SO' === $right) {
                    return false;
                }

                return $left <= $right && is_numeric($left) && is_numeric($right);
            case 'not in':
                return !in_array($left, $right);
            case 'in':
                return in_array($left, $right);
            case '+':
                if ('#NC' === $left || '#NC' === $right) {
                    return '#NC';
                } elseif ('#NA' === $left || '#NA' === $right) {
                    return '#NA';
                } elseif ('#SO' === $left || '#SO' === $right) {
                    return '#SO';
                }

                return $left + $right;
            case '-':
                if ('#NC' === $left || '#NC' === $right) {
                    return '#NC';
                } elseif ('#NA' === $left || '#NA' === $right) {
                    return '#NA';
                } elseif ('#SO' === $left || '#SO' === $right) {
                    return '#SO';
                }

                return $left - $right;
            case '~':
                return $left.$right;
            case '*':
                if ('#NC' === $left || '#NC' === $right) {
                    return '#NC';
                } elseif ('#NA' === $left || '#NA' === $right) {
                    return '#NA';
                } elseif ('#SO' === $left || '#SO' === $right) {
                    return '#SO';
                }

                return $left * $right;
            case '/':
                if ('#NC' === $left || '#NC' === $right) {
                    return '#NC';
                } elseif ('#NA' === $left || '#NA' === $right) {
                    return '#NA';
                } elseif ('#SO' === $left || '#SO' === $right) {
                    return '#SO';
                }

                return $left / $right;
            case '%':
                return $left % $right;
            case 'matches':
                return preg_match($right, $left);
        }
    }
}
