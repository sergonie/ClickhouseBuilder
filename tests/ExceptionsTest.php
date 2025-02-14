<?php

namespace Tinderbox\ClickhouseBuilder;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Tinderbox\Clickhouse\Client;
use Tinderbox\ClickhouseBuilder\Exceptions\BuilderException;
use Tinderbox\ClickhouseBuilder\Exceptions\GrammarException;
use Tinderbox\ClickhouseBuilder\Exceptions\NotSupportedException;
use Tinderbox\ClickhouseBuilder\Query\Builder;
use Tinderbox\ClickhouseBuilder\Query\JoinClause;

class ExceptionsTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function getBuilder(): Builder
    {
        return new Builder(m::mock(Client::class));
    }

    public function test_builder_exception()
    {
        $e = BuilderException::cannotDetermineAliasForColumn();
        $this->assertInstanceOf(BuilderException::class, $e);
    }

    public function test_grammar_exception()
    {
        $e = GrammarException::missedTableForInsert();
        $this->assertInstanceOf(GrammarException::class, $e);

        $e = GrammarException::wrongFrom();
        $this->assertInstanceOf(GrammarException::class, $e);

        $join = new JoinClause($this->getBuilder());

        $e = GrammarException::wrongJoin($join);
        $this->assertInstanceOf(GrammarException::class, $e);

        $e = GrammarException::ambiguousJoinKeys();
        $this->assertInstanceOf(GrammarException::class, $e);
    }

    public function test_not_supported_exception()
    {
        $e = NotSupportedException::transactions();
        $this->assertInstanceOf(NotSupportedException::class, $e);

        $e = NotSupportedException::update();
        $this->assertInstanceOf(NotSupportedException::class, $e);
    }
}
