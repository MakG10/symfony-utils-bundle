<?php

namespace MakG\SymfonyUtilsBundle\Doctrine\QueryFilter\Filter;


use Doctrine\ORM\QueryBuilder;

interface FilterInterface
{
    /**
     * Alters query based on filter and its value.
     *
     * @param QueryBuilder $queryBuilder
     * @param string $filterName
     * @param mixed $value
     */
    public function filter(QueryBuilder $queryBuilder, string $filterName, $value): void;

    /**
     * Checks whether a filter class supports a filter with given name.
     *
     * @param QueryBuilder $queryBuilder
     * @param string $filterName
     * @return bool
     */
    public function supports(QueryBuilder $queryBuilder, string $filterName): bool;
}
