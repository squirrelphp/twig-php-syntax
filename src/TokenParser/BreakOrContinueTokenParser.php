<?php

namespace Squirrel\TwigPhpSyntax\TokenParser;

use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

abstract class BreakOrContinueTokenParser extends AbstractTokenParser
{
    public function parse(Token $token): Node
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        // How many loops to break out of
        $loopNumber = 1;

        $numberToken = $stream->nextIf(Token::NUMBER_TYPE);

        if (isset($numberToken)) {
            $loopNumber = $numberToken->getValue();
        }

        if ($loopNumber > 1 && $this->getTag() === 'continue') {
            throw new SyntaxError(
                \ucfirst($this->getTag()) . ' tag cannot be used with a number higher than 1.',
                $stream->getCurrent()->getLine(),
                $stream->getSourceContext()
            );
        }

        $stream->expect(Token::BLOCK_END_TYPE);

        // Count how many loops are starting minus the loops ending
        $loopCount = 0;

        for ($i = 1; true; $i++) {
            try {
                // Look ahead to find for and endfor tokens to make sure
                // there are more loops ending than starting
                $token = $stream->look($i);
            } catch (SyntaxError $e) {
                // End of template, leading to SyntaxError
                break;
            }

            // Count both "for" loops and "foreach" loops
            if ($token->test(Token::NAME_TYPE, 'for') || $token->test(Token::NAME_TYPE, 'foreach')) {
                $loopCount++;
            } elseif ($token->test(Token::NAME_TYPE, 'endfor') || $token->test(Token::NAME_TYPE, 'endforeach')) {
                $loopCount--;
            }
        }

        // There should be more loops ending than starting, making loopCount negative
        if ($loopCount >= 0) {
            throw new SyntaxError(
                \ucfirst($this->getTag()) . ' tag is only allowed in \'for\' or \'foreach\' loops.',
                $stream->getCurrent()->getLine(),
                $stream->getSourceContext()
            );
        } elseif (\abs($loopCount) < $loopNumber) {
            throw new SyntaxError(
                \ucfirst($this->getTag()) . ' tag uses a loop number higher than the actual loops in this context - you are using the number ' . $loopNumber . ' but in the given context the maximum number is ' . \abs($loopCount) . '.',
                $stream->getCurrent()->getLine(),
                $stream->getSourceContext()
            );
        }

        /**
         * @var class-string<Node> $nodeClass
         */
        $nodeClass = __NAMESPACE__ . '\\' . \ucfirst($this->getTag()) . 'Node';

        return new $nodeClass($loopNumber, $lineno, $this->getTag());
    }
}
