<?php

namespace Tinderbox\ClickhouseBuilder\Query\Traits;

use Tinderbox\ClickhouseBuilder\Query\BaseBuilder;
use Tinderbox\ClickhouseBuilder\Query\Column;

trait ColumnsComponentCompiler
{
    use ColumnCompiler;

    /**
     * Compiles columns for select statement.
     *
     * @param  Column[]  $columns
     */
    private function compileColumnsComponent(BaseBuilder $builder, array $columns): string
    {
        $compiledColumns = [];

        foreach ($columns as $column) {
            $compiledColumns[] = $this->compileColumn($column);
        }

        return implode(', ', $compiledColumns);
    }
}
