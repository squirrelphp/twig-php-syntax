<?php

namespace Squirrel\TwigPhpSyntax\TokenParser;

use Twig\Node\Node;

final class ContinueTokenParser extends BreakOrContinueTokenParser
{
    #[\Override]
    public function getTag(): string
    {
        return 'continue';
    }

    #[\Override]
    protected function getNodeObject(int $loopNumber, int $lineno): Node
    {
        return new ContinueNode($loopNumber, $lineno);
    }
}
