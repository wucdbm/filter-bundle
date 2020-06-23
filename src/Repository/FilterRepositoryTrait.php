<?php

/*
 * This file is part of the WucdbmFilterBundle package.
 *
 * Copyright (c) Martin Kirilov <wucdbm@gmail.com>
 *
 * Author Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Bundle\WucdbmFilterBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Wucdbm\Bundle\WucdbmFilterBundle\Filter\AbstractFilter;

trait FilterRepositoryTrait {

    public function filterEntities(
        QueryBuilder $builder, AbstractFilter $filter, ?string $groupBy = null
    ): array {
        $pagination = $filter->getPagination();
        $options = $filter->getOptions();

        if ($pagination->getLimit()) {
            $builder->setMaxResults($pagination->getLimit());
        }
        $builder->setFirstResult($pagination->getOffset());

        if ($filter->isPaginated()) {
            $query = $builder->getQuery();
            $query->setHydrationMode($options->getHydrationMode());
            $paginator = new Paginator($query, true);
            $pagination->setResults(count($paginator));

            return $paginator->getIterator()->getArrayCopy();
        }

        if ($groupBy && $options->isHydrationArray()) {
            $builder->groupBy($groupBy);
        }

        $query = $builder->getQuery();

        return $query->getResult($options->getHydrationMode());
    }
}
