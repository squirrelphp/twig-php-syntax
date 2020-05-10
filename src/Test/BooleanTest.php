<?php

namespace Squirrel\TwigPhpSyntax\Test;

use Twig\Compiler;
use Twig\Node\Expression\TestExpression;

/**
 * Checks that a variable is a boolean.
 *
 *  {{ var is boolean }}
 *  {{ var is bool }}
 */
class BooleanTest extends TestExpression
{
    public function compile(Compiler $compiler): void
    {
        $compiler
            ->raw('(true === \\is_bool(')
            ->subcompile($this->getNode('node'))
            ->raw('))')
        ;
    }
}
