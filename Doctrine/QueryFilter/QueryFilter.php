<?php

namespace MakG\SymfonyUtilsBundle\Doctrine\QueryFilter;


use Doctrine\ORM\QueryBuilder;
use MakG\SymfonyUtilsBundle\Console\Input\SearchQueryInput;
use MakG\SymfonyUtilsBundle\Doctrine\QueryFilter\Filter\FilterInterface;
use MakG\SymfonyUtilsBundle\Exception\QueryFilterNotFoundException;

class QueryFilter
{
    /** @var FilterInterface[] */
    private $queryFilters;

    public function __construct(iterable $queryFilters)
    {
        $this->queryFilters = $queryFilters;
    }

    /**
     * Alters query based on search string.
     * Search string is expected to be in format: `filter1:value filter2:"second value"`
     *
     * @throws QueryFilterNotFoundException
     */
    public function filter(QueryBuilder $queryBuilder, string $searchQuery): void
    {
        $searchInput = new SearchQueryInput($searchQuery);

        foreach ($searchInput->getOptions() as $filterName => $value) {
            $queryFilter = $this->getMatchingFilter($queryBuilder, $filterName);
            $queryFilter->filter($queryBuilder, $filterName, $value);
        }
    }

    /**
     * Returns the first QueryFilter supporting filter with given name.
     *
     * @throws QueryFilterNotFoundException
     */
    private function getMatchingFilter(QueryBuilder $queryBuilder, string $filterName): FilterInterface
    {
        foreach ($this->queryFilters as $queryFilter) {
            if ($queryFilter->supports($queryBuilder, $filterName)) {
                return $queryFilter;
            }
        }

        throw new QueryFilterNotFoundException(
            sprintf('QueryFilter supporting "%s" filter was not found.', $filterName)
        );
    }
}
