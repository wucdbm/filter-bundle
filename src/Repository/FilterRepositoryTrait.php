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
        QueryBuilder $builder, AbstractFilter $filter
    ): array {
        if ($filter->getLimit()) {
            $builder->setMaxResults($filter->getLimit());
        }

        $builder->setFirstResult($filter->getOffset());

        $query = $builder->getQuery();
        $paginator = new Paginator($query, true);
        $filter->setResults(count($paginator));

        return $paginator->getIterator()->getArrayCopy();
    }
}
