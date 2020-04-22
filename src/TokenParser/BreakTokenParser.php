<?php

namespace Squirrel\TwigPhpSyntax\TokenParser;

class BreakTokenParser extends BreakOrContinueTokenParser
{
    public function getTag()
    {
        return 'break';
    }
}
