<?php

declare(strict_types=1);

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
use Doctrine\ORM\Tools\Pagination\OffsetPaginator;
use Doctrine\ORM\Tools\Pagination\Window;
use Wucdbm\Bundle\WucdbmFilterBundle\Filter\AbstractFilter;

trait FilterRepositoryTrait
{
    public function filterEntities(
        QueryBuilder $builder, AbstractFilter $filter,
    ): array {
        $query = $builder->getQuery();
        $paginator = new OffsetPaginator(true);
        $page = $paginator->paginate(
            $query,
            new Window(
                $filter->getOffset(),
                $filter->getLimit(),
            )
        );
        $filter->setResults($page->getTotalCount());

        return $page->getIterator()->getArrayCopy();
    }
}
