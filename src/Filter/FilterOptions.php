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

class FilterOptions {

    public const OPTION_HYDRATION = 'hydration';
    public const OPTION_PAGE_VAR = 'page';
    public const OPTION_LIMIT_VAR = 'limit';

    /* Hydration mode constants */
    /**
     * Hydrates an object graph. This is the default behavior.
     */
    public const OPTION_HYDRATION_OBJECT = 1;

    /**
     * Hydrates an array graph.
     */
    public const OPTION_HYDRATION_ARRAY = 2;

    /**
     * Hydrates a flat, rectangular result set with scalar values.
     */
    public const OPTION_HYDRATION_SCALAR = 3;

    /**
     * Hydrates a single scalar value.
     */
    public const OPTION_HYDRATION_SINGLE_SCALAR = 4;

    /**
     * Very simple object hydrator (optimized for performance).
     */
    public const OPTION_HYDRATION_SIMPLEOBJECT = 5;

    private array $options = [
        self::OPTION_HYDRATION => self::OPTION_HYDRATION_OBJECT,
        self::OPTION_PAGE_VAR => 'page',
        self::OPTION_LIMIT_VAR => 'limit'
    ];

    public function getHydrationMode() {
        return $this->getOption(self::OPTION_HYDRATION);
    }

    public function setHydrationObject(): self {
        return $this->setOption(self::OPTION_HYDRATION, self::OPTION_HYDRATION_OBJECT);
    }

    public function setHydrationArray(): self {
        return $this->setOption(self::OPTION_HYDRATION, self::OPTION_HYDRATION_ARRAY);
    }

    public function isHydrationArray(): bool {
        return $this->isOption(self::OPTION_HYDRATION, self::OPTION_HYDRATION_ARRAY);
    }

    public function getOption($name) {
        return $this->options[$name];
    }

    public function setOption($name, $value): self {
        $this->options[$name] = $value;

        return $this;
    }

    public function isOption($name, $value): bool {
        return $this->options[$name] === $value;
    }

    public function getPageVar(): string {
        return $this->getOption(self::OPTION_PAGE_VAR);
    }

    public function getLimitVar(): string {
        return $this->getOption(self::OPTION_LIMIT_VAR);
    }

    public function setPageVar(string $var): self {
        $this->setOption(self::OPTION_PAGE_VAR, $var);

        return $this;
    }

    public function setLimitVar(string $var): self {
        $this->setOption(self::OPTION_LIMIT_VAR, $var);

        return $this;
    }
}
