<?php

namespace Squirrel\TwigPhpSyntax\TokenParser;

use Twig\Node\Node;

final class BreakTokenParser extends BreakOrContinueTokenParser
{
    #[\Override]
    public function getTag(): string
    {
        return 'break';
    }

    #[\Override]
    protected function getNodeObject(int $loopNumber, int $lineno): Node
    {
        return new BreakNode($loopNumber, $lineno);
    }
}
