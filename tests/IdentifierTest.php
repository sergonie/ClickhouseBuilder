<?php

namespace Tinderbox\ClickhouseBuilder;

use PHPUnit\Framework\TestCase;
use Tinderbox\ClickhouseBuilder\Query\Identifier;

class IdentifierTest extends TestCase
{
    public function test_to_string()
    {
        $identifier = new Identifier('column');

        $this->assertEquals('column', (string)$identifier);
    }
}
