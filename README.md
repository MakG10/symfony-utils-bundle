# Symfony Utils Bundle

Bundle for Symfony 4.x with various useful stuff. It includes:
- `QueryFilter` for Doctrine ORM to support custom search string queries like `symfony bundle site:github.com in-title:API`
- `@CsrfTokenRequired` annotation to force presence of a valid CSRF token in HTTP header when dispatching controller action
- Twig Functions: `path_js`, `light_colors`
- Twig Filters: `color`

Author: Maciej Gierej - http://maciej.gierej.pl

## Installation

```
composer req makg/symfony-utils-bundle
```

## @CsrfTokenRequired annotation

Usage:

```php
<?php

namespace App\Controller;

use MakG\SymfonyUtilsBundle\Annotation\CsrfTokenRequired;
use Symfony\Component\Routing\Annotation\Route;

class VotesController
{
    /**
     * @Route("/vote", name="votes_vote")
     * @CsrfTokenRequired(id="votes", header="X-CSRF-Token", param="token")
     */
    public function vote()
    {
        // ...
    }
}
```

You can i.e. pass the CSRF token in the URL in Twig:

```twig
{{ path('votes_vote', {token: csrf_token('votes')}) }}
```

| Annotation param | Required | Default | Description |
| ---------------- | -------- | ------- | ----------- |
| id               | Yes      | -       | ID of generated CSRF token |
| header           | No       | X-CSRF-Token | HTTP Header name from which the token will be read. If the token is not found in the header, then "param" is checked in request's parameters bag. |
| param            | No       | token   | Name of request's parameter containing CSRF token |


## Query Filter

Query Filter provided in this bundle allows handling search query strings like `symfony bundle site:github.com in-title:API`. Each filter (site, in-title) can be handled by user's custom filter implementing `FilterInterface`.

In the example search query string above, the input will split into:

- Search phrases: `symfony bundle` - they are not handled by Query Filters. You can extract them using provided `SearchQueryInput` class based on symfony/console's StringInput parser. You can use static helper method: `SearchQueryInput::getPhrasesFromInput('input')`.
- Filters: `site`, `in-title`. If there is a service implementing `FilterInterface` tagged with `makg.query_filter` registered in the container supporting filter with given name, then it will be applied to passed `QueryBuilder`.

This bundle provides one universal filter - `IdFilter`. You can take a look at it in `Doctrine/QueryFilter/Filter/IdFilter.php` to get an idea how to implement your own filters.

**Usage:**

You can inject `QueryFilter` service using autowiring.

```php
<?php

use Doctrine\ORM\QueryBuilder;
use MakG\SymfonyUtilsBundle\Doctrine\QueryFilter\QueryFilter;

/** @var QueryBuilder $queryBuilder */
/** @var QueryFilter $queryFilter */

$queryBuilder
    ->select('entity.id, entity.name');

$queryFilter->filter($queryBuilder, 'search phrase id:123');
```

## Twig Functions

### path_js

This function renders path from Router without resolved parameters, even if they are required. Useful when you want to pass application's route to JavaScript functions and substitute parameters on client side.

**Usage:**

```twig
{# Route with "products_vote" name: /products/{id}/vote #}
<script type="text/javascript">
    let id = 123;
    let voteUrl = '{{ path_js('products_vote') }}'.replace('{id}', id);
</script>
```
