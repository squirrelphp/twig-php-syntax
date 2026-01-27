<?php

namespace Squirrel\TwigPhpSyntax\Test;

use Twig\Compiler;
use Twig\Node\Expression\TestExpression;

/**
 * Checks that a variable is an array.
 *
 *  {{ var is array }}
 */
final class ArrayTest extends TestExpression
{
    #[\Override]
    public function compile(Compiler $compiler): void
    {
        $compiler
            ->raw('(true === \\is_array(')
            ->subcompile($this->getNode('node'))
            ->raw('))')
        ;
    }
}
