<?php

namespace Squirrel\TwigPhpSyntax\Test;

use Twig\Compiler;
use Twig\Node\Expression\TestExpression;

/**
 * Checks that a variable is a float.
 *
 *  {{ var is float }}
 */
final class FloatTest extends TestExpression
{
    #[\Override]
    public function compile(Compiler $compiler): void
    {
        $compiler
            ->raw('(true === \\is_float(')
            ->subcompile($this->getNode('node'))
            ->raw('))')
        ;
    }
}
