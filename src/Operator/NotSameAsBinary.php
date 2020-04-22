<?php

namespace Squirrel\TwigPhpSyntax\Operator;

use Twig\Compiler;
use Twig\Node\Expression\Binary\AbstractBinary;

class NotSameAsBinary extends AbstractBinary
{
    public function compile(Compiler $compiler): void
    {
        parent::compile($compiler);
    }

    public function operator(Compiler $compiler): Compiler
    {
        return $compiler->raw('!==');
    }
}
