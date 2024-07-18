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

class AbstractFilter {

    private int $page = 1;
    private int $limit = 20;
    private int $results = 0;

    final public function getPage(): ?int {
        return $this->page;
    }

    final public function setPage(int $page): void {
        $this->page = $page;
    }

    final public function getLimit(): ?int {
        return $this->limit;
    }

    final public function setLimit(int $limit): void {
        $this->limit = $limit;
    }

    final public function getResults(): int {
        return $this->results;
    }

    final public function setResults(int $results): void {
        $this->results = $results;
    }

    final public function getOffset(): int {
        $pages = $this->page - 1;

        if ($pages < 0) {
            return 0;
        }

        return $pages * $this->limit;
    }

    final public function getPages(): int {
        if (!$this->limit) {
            return 0;
        }

        return ceil($this->results / $this->limit);
    }
}
