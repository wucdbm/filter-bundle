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

namespace Wucdbm\Bundle\WucdbmFilterBundle\Filter;

class Pagination {

    private AbstractFilter $filter;
    private int $page = 1;
    private int $limit = 20;
    private int $results = 0;

    public function __construct(AbstractFilter $filter) {
        $this->filter = $filter;
    }

    private function sanitizeInt($value, int $default): int {
        $value = filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

        if (null === $value) {
            return $default;
        }

        return $value;
    }

    public function setPage($page): self {
        $this->page = $this->sanitizeInt($page, $this->page);

        return $this;
    }

    public function setLimit($limit): self {
        $this->limit = $this->sanitizeInt($limit, $this->limit);

        return $this;
    }

    public function setResults(int $results): self {
        $this->results = $results;

        return $this;
    }

    public function getFilter(): AbstractFilter {
        return $this->filter;
    }

    public function getPage(): int {
        return $this->page;
    }

    public function getLimit(): int {
        return $this->limit;
    }

    public function getResults(): int {
        return $this->results;
    }

    public function getOffset(): int {
        $pages = $this->page - 1;

        if ($pages < 0) {
            return 0;
        }

        return $pages * $this->limit;
    }

    public function getPages(): int {
        if (!$this->limit) {
            return 0;
        }

        return ceil($this->results / $this->limit);
    }
}
