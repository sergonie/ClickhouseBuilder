<?php

namespace Tinderbox\ClickhouseBuilder\Query\Traits;

use Tinderbox\ClickhouseBuilder\Query\Tuple;

trait TupleCompiler
{
    /**
     * Compiles tuple to string to use this string in query.
     */
    public function compileTuple(Tuple $tuple): string
    {
        return implode(', ', array_map([$this, 'wrap'], $tuple->getElements()));
    }
}
