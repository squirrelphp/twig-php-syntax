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
final class IntegerTest extends TestExpression
{
    #[\Override]
    public function compile(Compiler $compiler): void
    {
        $compiler
            ->raw('(true === \\is_int(')
            ->subcompile($this->getNode('node'))
            ->raw('))')
        ;
    }
}
