<?php

namespace Tinderbox\ClickhouseBuilder\Query\Traits;

use Tinderbox\ClickhouseBuilder\Query\BaseBuilder as Builder;
use Tinderbox\ClickhouseBuilder\Query\TwoElementsLogicExpression;

trait PreWheresComponentCompiler
{
    /**
     * Compiles prewhere to string to pass this string in query.
     *
     * @param  TwoElementsLogicExpression[]  $preWheres
     */
    public function compilePrewheresComponent(Builder $builder, array $preWheres): string
    {
        $result = $this->compileTwoElementLogicExpressions($preWheres);

        return "PREWHERE {$result}";
    }
}
