<?php

namespace Squirrel\TwigPhpSyntax\TokenParser;

use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\Node;

#[YieldReady]
class ContinueNode extends Node
{
    public function __construct(
        private int $loopNumber,
        int $lineno,
    ) {
        parent::__construct([], [], $lineno);
    }

    public function compile(Compiler $compiler): void
    {
        $compiler
            ->addDebugInfo($this)
            ->write("if (isset(\$context['loop'])) {\n")
            ->indent()
            // Taken from ForLoopNode - need to do this otherwise the continue skips it
            ->write("++\$context['loop']['index0'];\n")
            ->write("++\$context['loop']['index'];\n")
            ->write("\$context['loop']['first'] = false;\n")
            ->write("if (isset(\$context['loop']['length'])) {\n")
            ->indent()
            ->write("--\$context['loop']['revindex0'];\n")
            ->write("--\$context['loop']['revindex'];\n")
            ->write("\$context['loop']['last'] = 0 === \$context['loop']['revindex0'];\n")
            ->outdent()
            ->write("}\n")
            // End taken from FoorLoopNode
            ->outdent()
            ->write("}\n")
            // Do the actual continue operation
            ->write("continue " . $this->loopNumber . ";\n")
        ;
    }
}
