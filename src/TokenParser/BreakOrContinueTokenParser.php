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

        // Trick to check if we are currently in a loop.
        $currentForLoop = 0;

        for ($i = 1; true; $i++) {
            // If we look back too far in the stream twig will throw a SyntaxError
            // and there we often get another error because an illegal offset is accessed
            // when creating the SyntaxError, so we catch all throwables to be safe
            try {
                // We look back by using a negative integer, which is not intended by twig and seems
                // a bit hacky, but does work for our purposes currently and gives us the opportunity
                // to detect illegal usages of break/continue outside of loops or in not-enough-nested contexts
                $token = $stream->look(-$i);
            } catch (\Throwable $e) {
                break;
            }

            // Count both "for" loops and "foreach" loops
            if ($token->test(Token::NAME_TYPE, 'for') || $token->test(Token::NAME_TYPE, 'foreach')) {
                $currentForLoop++;
            } elseif ($token->test(Token::NAME_TYPE, 'endfor') || $token->test(Token::NAME_TYPE, 'endforeach')) {
                $currentForLoop--;
            }
        }


        if ($currentForLoop < 1) {
            throw new SyntaxError(
                \ucfirst($this->getTag()) . ' tag is only allowed in \'for\' or \'foreach\' loops.',
                $stream->getCurrent()->getLine(),
                $stream->getSourceContext()
            );
        } elseif ($currentForLoop < $loopNumber) {
            throw new SyntaxError(
                \ucfirst($this->getTag()) . ' tag uses a loop number higher than the actual loops in this context - you are using the number ' . $loopNumber . ' but in the given context the maximum number is ' . $currentForLoop . '.',
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
