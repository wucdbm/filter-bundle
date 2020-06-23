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

use ReflectionClass;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class AbstractFilter {

    private Pagination $_pagination;
    private FilterOptions $_options;
    private ?array $_fields = null;
    private bool $_paginated = false;

    public function loadRequest(Request $request, string $method = 'GET', string $namespace = '', $paginate = true): void {
        $data = $this->getData($request, $method, $namespace);
        $this->loadPageAndLimit($data, $paginate);

        foreach ($this->getFields() as $field) {
            $val = $data[$field] ?? null;

            if ($val) {
                $setter = 'set'.ucfirst($field);
                if (method_exists($this, $setter)) {
                    $this->$setter($val);
                } else {
                    $this->$field = $val;
                }
            }
        }
    }

    public function loadForm(Request $request, FormInterface $form, bool $paginate = true): void {
        $data = $this->getData($request, $form->getConfig()->getMethod(), $form->getName());
        $this->loadPageAndLimit($data, $paginate);
        $form->submit($data);
    }

    protected function loadPageAndLimit(array $data, bool $paginate): void {
        $this->_paginated = $paginate;

        $page = $data[$this->_options->getPageVar()] ?? 1;
        $this->_pagination->setPage($page);

        if (isset($data[$this->_options->getLimitVar()])) {
            $this->_pagination->setLimit($data[$this->_options->getLimitVar()]);
        }
    }

    protected function getData(Request $request, string $method, string $namespace): array {
        return $this->getDataForNamespace(
            $this->getBagForMethod($request, $method),
            $namespace
        );
    }

    protected function getDataForNamespace(ParameterBag $bag, string $namespace) {
        return $namespace ? $bag->get($namespace, []) : $bag->all();
    }

    protected function getBagForMethod(Request $request, string $method): ParameterBag {
        if ('POST' === $method) {
            return $request->request;
        }

        if ('GET' === $method) {
            return $request->query;
        }

        return $request->query;
    }

    protected function getFields(): array {
        if (null === $this->_fields) {
            $reflection = new ReflectionClass($this);
            $properties = $reflection->getProperties();

            $this->_fields = [];

            foreach ($properties as $property) {
                $field = $property->getName();

                if (0 === strpos($field, '_')) {
                    continue;
                }

                $this->_fields[] = $field;
            }
        }

        return $this->_fields;
    }

    public function __construct() {
        $this->_pagination = new Pagination($this);
        $this->_options = new FilterOptions();
    }

    public function getPagination(): Pagination {
        return $this->_pagination;
    }

    public function getOptions(): FilterOptions {
        return $this->_options;
    }

    public function getLimit(): ?int {
        return $this->_pagination->getLimit();
    }

    public function getPage(): ?int {
        return $this->_pagination->getPage();
    }

    public function isPaginated(): bool {
        return $this->_paginated;
    }
}
