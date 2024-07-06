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

    private Pagination $pagination;
    private FilterOptions $options;
    private ?array $fields = null;
    /**
     * @deprecated
     */
    private bool $paginated = true;

    /**
     * @deprecated
     */
    public function loadRequest(Request $request, string $namespace = '', bool $paginate = true): void {
        $data = $this->getData($request, $request->getMethod(), $namespace);
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

    /**
     * @deprecated
     */
    public function loadForm(Request $request, FormInterface $form, bool $paginate = true): void {
        $data = $this->getData($request, $form->getConfig()->getMethod(), $form->getName());
        $this->loadPageAndLimit($data, $paginate);
        $form->submit($data);
    }

    protected function loadPageAndLimit(array $data, bool $paginate): void {
        $this->paginated = $paginate;

        $page = $data[$this->options->getPageVar()] ?? 1;
        $this->pagination->setPage($page);

        if (isset($data[$this->options->getLimitVar()])) {
            $this->pagination->setLimit($data[$this->options->getLimitVar()]);
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
        if (null === $this->fields) {
            $reflection = new ReflectionClass($this);
            $properties = $reflection->getProperties();

            $this->fields = [];

            foreach ($properties as $property) {
                $field = $property->getName();

                if (str_starts_with($field, '_')) {
                    continue;
                }

                $this->fields[] = $field;
            }
        }

        return $this->fields;
    }

    /**
     * @deprecated
     */
    final public function paginate(): self {
        $this->paginated = true;

        return $this;
    }

    public function __construct() {
        $this->pagination = new Pagination($this);
        $this->options = new FilterOptions();
    }

    final public function getPagination(): Pagination {
        return $this->pagination;
    }

    final public function getOptions(): FilterOptions {
        return $this->options;
    }

    final public function getOption(string $name): mixed {
        return $this->options->getOption($name);
    }

    /**
     * @deprecated
     */
    final public function isPaginated(): bool {
        return $this->paginated;
    }

    final public function getPage(): ?int {
        return $this->pagination->getPage();
    }

    final public function getLimit(): ?int {
        return $this->pagination->getLimit();
    }

    final public function getResults(): int {
        return $this->pagination->getResults();
    }

    final public function getOffset(): int {
        return $this->pagination->getOffset();
    }

    final public function getPages(): int {
        return $this->pagination->getPages();
    }

    final public function setPage($page): self {
        $this->pagination->setPage($page);

        return $this;
    }

    final public function setLimit($limit): self {
        $this->pagination->setLimit($limit);

        return $this;
    }

    final public function setResults(int $results): self {
        $this->pagination->setResults($results);

        return $this;
    }
}
