<?php

namespace MakG\SymfonyUtilsBundle\Doctrine\QueryFilter;


use Doctrine\ORM\QueryBuilder;
use MakG\SymfonyUtilsBundle\Doctrine\QueryFilter\Filter\FilterInterface;
use PHPUnit\Framework\TestCase;

class QueryFilterTest extends TestCase
{
    public function testFilter()
    {
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $searchQuery = 'test template:container test2';

        $templateFilter = $this->createMock(FilterInterface::class);
        $templateFilter
            ->expects($this->once())
            ->method('filter')
            ->with($queryBuilder, 'template', ['container']);
        $templateFilter
            ->method('supports')
            ->willReturn(true);

        $queryFilter = new QueryFilter([$templateFilter]);

        $queryFilter->filter($queryBuilder, $searchQuery);
    }
}
