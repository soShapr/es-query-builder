<?php

namespace esQueryBuilder\Test;

use PHPUnit\Framework\TestCase;
use esQueryBuilder\queryBuilder;

class queryBuilderTest extends TestCase
{
    public function jobSearch()
    {
        return $this->assertEquals(queryBuilder::jobSearch("consultant"), true);
    }
}
