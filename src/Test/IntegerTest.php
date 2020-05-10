<?php

namespace Squirrel\TwigPhpSyntax\Test;

use Twig\Compiler;
use Twig\Node\Expression\TestExpression;

/**
 * Checks that a variable is an integer.
 *
 *  {{ var is int }}
 *  {{ var is integer }}
 */
class IntegerTest extends TestExpression
{
    public function compile(Compiler $compiler): void
    {
        $compiler
            ->raw('(true === \\is_int(')
            ->subcompile($this->getNode('node'))
            ->raw('))')
        ;
    }
}
