<?php

namespace MakG\SymfonyUtilsBundle\Doctrine\QueryFilter\Filter;


use Doctrine\ORM\QueryBuilder;

class IdFilter implements FilterInterface
{
    use FilterTrait;

    /**
     * {@inheritDoc}
     */
    public function filter(QueryBuilder $queryBuilder, string $filterName, $value): void
    {
        $ids = (array)$value;

        $idFieldPath = static::pathFromRoot($queryBuilder, 'id');
        $paramName = static::createAlias('id');

        $queryBuilder
            ->andWhere("$idFieldPath IN (:$paramName)")
            ->setParameter($paramName, $ids);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(QueryBuilder $queryBuilder, string $filterName): bool
    {
        return $filterName === 'id' && static::hasField($queryBuilder, 'id');
    }
}
