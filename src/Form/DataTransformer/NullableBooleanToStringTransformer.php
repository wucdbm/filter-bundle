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

namespace Wucdbm\Bundle\WucdbmFilterBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Based on and same as
 * Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformerNew
 * Except for allowing null as the model value.
 */
class NullableBooleanToStringTransformer implements DataTransformerInterface {

    private string $trueValue;
    private array $falseValues;

    public function __construct(string $trueValue, array $falseValues = [null]) {
        $this->trueValue = $trueValue;
        $this->falseValues = $falseValues;

        if (\in_array($this->trueValue, $this->falseValues, true)) {
            throw new InvalidArgumentException('The specified "true" value is contained in the false-values.');
        }
    }

    /**
     * Transforms a Boolean into a string.
     *
     * @param bool $value Boolean value
     *
     * @return string|null String value
     *
     * @throws TransformationFailedException if the given value is not a Boolean
     */
    public function transform($value): ?string {
        if (null === $value) {
            return null;
        }

        if (!\is_bool($value)) {
            throw new TransformationFailedException('Expected a Boolean.');
        }

        return $value ? $this->trueValue : null;
    }

    /**
     * Transforms a string into a Boolean.
     *
     * @param string $value String value
     *
     * @return bool Boolean value
     *
     * @throws TransformationFailedException if the given value is not a string
     */
    public function reverseTransform($value): ?bool {
        if (null === $value) {
            return null;
        }

        if (\in_array($value, $this->falseValues, true)) {
            return false;
        }

        if (!\is_string($value)) {
            throw new TransformationFailedException('Expected a string.');
        }

        return true;
    }
}
