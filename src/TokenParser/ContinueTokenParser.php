<?php

namespace Squirrel\TwigPhpSyntax\TokenParser;

class ContinueTokenParser extends BreakOrContinueTokenParser
{
    public function getTag()
    {
        return 'continue';
    }
}
