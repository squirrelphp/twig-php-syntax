<?php

namespace Squirrel\TwigPhpSyntax\Test;

use Twig\Compiler;
use Twig\Node\Expression\TestExpression;

/**
 * Checks that a variable is an object.
 *
 *  {{ var is object }}
 */
final class ObjectTest extends TestExpression
{
    #[\Override]
    public function compile(Compiler $compiler): void
    {
        $compiler
            ->raw('(true === \\is_object(')
            ->subcompile($this->getNode('node'))
            ->raw('))')
        ;
    }
}
