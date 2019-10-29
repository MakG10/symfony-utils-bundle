<?php

namespace MakG\SymfonyUtilsBundle\Doctrine\QueryFilter;


use Doctrine\ORM\QueryBuilder;

interface QueryFilterInterface
{
    public function filter(QueryBuilder $queryBuilder, string $searchQuery): void;
}
