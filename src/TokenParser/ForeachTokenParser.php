<?php

namespace Squirrel\TwigPhpSyntax\TokenParser;

use Twig\Error\SyntaxError;
use Twig\Lexer;
use Twig\Node\Expression\AssignNameExpression;
use Twig\Node\ForNode;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class ForeachTokenParser extends AbstractTokenParser
{
    /*
     * Taken from ForTokenParser, we just exchanged small parts of it to support the slightly different syntax
     */
    public function parse(Token $token): Node
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $seq = $this->parser->getExpressionParser()->parseExpression();
        $stream->expect(Token::NAME_TYPE, 'as');
        $targets = $this->parseAssignmentExpression();

        $stream->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideForeachFork']);
        if ($stream->next()->getValue() === 'else') {
            $stream->expect(Token::BLOCK_END_TYPE);
            $else = $this->parser->subparse([$this, 'decideForeachEnd'], true);
        } else {
            $else = null;
        }
        $stream->expect(Token::BLOCK_END_TYPE);

        if (\count($targets) > 1) {
            $keyTarget = $targets->getNode('0');
            $keyTarget = new AssignNameExpression($keyTarget->getAttribute('name'), $keyTarget->getTemplateLine());
            $valueTarget = $targets->getNode('1');
            $valueTarget = new AssignNameExpression($valueTarget->getAttribute('name'), $valueTarget->getTemplateLine());
        } else {
            $keyTarget = new AssignNameExpression('_key', $lineno);
            $valueTarget = $targets->getNode('0');
            $valueTarget = new AssignNameExpression($valueTarget->getAttribute('name'), $valueTarget->getTemplateLine());
        }

        return new ForNode($keyTarget, $valueTarget, $seq, null, $body, $else, $lineno, $this->getTag());
    }

    public function decideForeachFork(Token $token): bool
    {
        return $token->test(['else', 'endforeach']);
    }

    public function decideForeachEnd(Token $token): bool
    {
        return $token->test('endforeach');
    }

    public function getTag(): string
    {
        return 'foreach';
    }

    /*
     * Taken from ExpressionParser::parseAssignmentExpression, we just exchanged the operator usage
     */
    private function parseAssignmentExpression(): Node
    {
        $stream = $this->parser->getStream();
        $targets = [];
        while (true) {
            $token = $this->parser->getCurrentToken();
            if ($stream->test(Token::OPERATOR_TYPE) && \preg_match(Lexer::REGEX_NAME, $token->getValue())) {
                // in this context, string operators are variable names
                $this->parser->getStream()->next();
            } else {
                $stream->expect(Token::NAME_TYPE, null, 'Only variables can be assigned to');
            }
            $value = $token->getValue();
            if (\in_array(\strtolower($value), ['true', 'false', 'none', 'null'], true)) {
                throw new SyntaxError(\sprintf('You cannot assign a value to "%s".', $value), $token->getLine(), $stream->getSourceContext());
            }
            $targets[] = new AssignNameExpression($value, $token->getLine());

            if (!$stream->nextIf(Token::ARROW_TYPE, '=>')) {
                break;
            }
        }

        return new Node($targets);
    }
}
