<?php

namespace Squirrel\TwigPhpSyntax\ExpressionParser;

use Twig\ExpressionParser\AbstractExpressionParser;
use Twig\ExpressionParser\InfixAssociativity;
use Twig\ExpressionParser\InfixExpressionParserInterface;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Expression\Binary\AbstractBinary;
use Twig\Parser;
use Twig\Token;

class BinaryOperatorExpressionParser extends AbstractExpressionParser implements InfixExpressionParserInterface
{
    public function __construct(
        /** @var class-string<AbstractBinary> $nodeClass */
        private string $nodeClass,
        private string $name,
        private int $precedence,
        private InfixAssociativity $associativity = InfixAssociativity::Left,
    ) {
    }

    /**
     * @return AbstractBinary
     */
    public function parse(Parser $parser, AbstractExpression $left, Token $token): AbstractExpression
    {
        $right = $parser->parseExpression($this->getAssociativity() === InfixAssociativity::Left ? $this->getPrecedence() + 1 : $this->getPrecedence());

        return new ($this->nodeClass)($left, $right, $token->getLine());
    }

    public function getAssociativity(): InfixAssociativity
    {
        return $this->associativity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrecedence(): int
    {
        return $this->precedence;
    }
}
