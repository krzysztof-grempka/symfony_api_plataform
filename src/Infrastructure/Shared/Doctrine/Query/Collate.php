<?php

namespace App\Infrastructure\Shared\Doctrine\Query;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class Collate extends FunctionNode
{
    public $stringPrimary;

    public $collation;

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->stringPrimary = $parser->StringPrimary();

        $parser->match(Lexer::T_COMMA);
        $parser->match(Lexer::T_IDENTIFIER);

        $lexer = $parser->getLexer();

        $this->collation = $lexer->token->value;

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return sprintf('%s COLLATE %s', $this->stringPrimary->dispatch($sqlWalker), $this->collation);
    }
}
