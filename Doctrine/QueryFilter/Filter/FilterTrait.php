<?php

namespace MakG\SymfonyUtilsBundle\Doctrine\QueryFilter\Filter;


use Doctrine\ORM\QueryBuilder;

/**
 * A bunch of helpers to create universal filters.
 */
trait FilterTrait
{
    protected static $uniqueAliasId = 0;

    /**
     * Generates a unique alias.
     */
    protected static function createAlias(string $name = 'param'): string
    {
        return str_replace('.', '_', $name).'_'.++static::$uniqueAliasId;
    }

    /**
     * Returns path to field from root table.
     */
    protected static function pathFromRoot(QueryBuilder $queryBuilder, string $fieldName): string
    {
        $alias = current($queryBuilder->getRootAliases());

        return "$alias.$fieldName";
    }

    /**
     * Checks if root entity has field with given name.
     */
    protected static function hasField(QueryBuilder $queryBuilder, string $fieldName): bool
    {
        $entityClass = current($queryBuilder->getRootEntities());
        $entityProperties = $queryBuilder->getEntityManager()->getClassMetadata($entityClass)->getFieldNames();

        return in_array($fieldName, $entityProperties, true);
    }
}
