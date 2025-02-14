<?php

namespace Tinderbox\ClickhouseBuilder;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Tinderbox\Clickhouse\Client;
use Tinderbox\ClickhouseBuilder\Query\Builder;
use Tinderbox\ClickhouseBuilder\Query\Enums\Operator;
use Tinderbox\ClickhouseBuilder\Query\TwoElementsLogicExpression;

class TwoElementsLogicExpressionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function getBuilder(): Builder
    {
        return new Builder(m::mock(Client::class));
    }

    public function test_plain_elements()
    {
        $builder = $this->getBuilder();
        $expression = new TwoElementsLogicExpression($builder);

        $expression->firstElement('column');
        $expression->secondElement('another_column');
        $expression->operator('=');
        $expression->concatOperator(Operator::OR);

        $this->assertEquals('column', $expression->getFirstElement());
        $this->assertEquals('another_column', $expression->getSecondElement());
        $this->assertEquals('=', $expression->getOperator());
        $this->assertEquals(Operator::OR, $expression->getConcatenationOperator());
    }

    public function test_builder_elements()
    {
        $builder = $this->getBuilder();
        $expression = new TwoElementsLogicExpression($builder);

        $expression->firstElementQuery($this->getBuilder()->select('column')->from('table'));
        $expression->secondElementQuery($this->getBuilder()->select('another_column')->from('table'));
        $expression->operator('=');
        $expression->concatOperator(Operator::OR);

        $this->assertEquals('(SELECT `column` FROM `table`)', $expression->getFirstElement());
        $this->assertEquals('(SELECT `another_column` FROM `table`)', $expression->getSecondElement());
        $this->assertEquals('=', $expression->getOperator());
        $this->assertEquals(Operator::OR, $expression->getConcatenationOperator());
    }

    public function test_closure_elements()
    {
        $builder = $this->getBuilder();
        $expression = new TwoElementsLogicExpression($builder);

        $expression->firstElementQuery(function ($builder) {
            return $builder->select('column')->from('table');
        });

        $expression->secondElementQuery(function ($builder) {
            return $builder->select('another_column')->from('table');
        });
        $expression->operator('=');
        $expression->concatOperator(Operator::OR);

        $this->assertEquals('(SELECT `column` FROM `table`)', $expression->getFirstElement());
        $this->assertEquals('(SELECT `another_column` FROM `table`)', $expression->getSecondElement());
        $this->assertEquals('=', $expression->getOperator());
        $this->assertEquals(Operator::OR, $expression->getConcatenationOperator());
    }
}
