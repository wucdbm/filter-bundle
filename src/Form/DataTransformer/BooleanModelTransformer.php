<?php

namespace Wucdbm\Bundle\WucdbmFilterBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class BooleanModelTransformer implements DataTransformerInterface
{

    private array $trueValues;
    private array $falseValues;
    private array $nullValues;
    private bool $required;

    public function __construct(
        array $trueValues,
        array $falseValues,
        array $nullValues,
        bool $required
    )
    {
        $this->trueValues = $trueValues;
        $this->falseValues = $falseValues;
        $this->nullValues = $nullValues;
        $this->required = $required;
    }

    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        if (!is_bool($value)) {
            throw new TransformationFailedException('Expected a boolean.');
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        if (!$this->required && in_array($value, $this->nullValues, true)) {
            return null;
        }

        if (in_array($value, $this->trueValues, true)) {
            return true;
        }

        if (in_array($value, $this->falseValues, true)) {
            return false;
        }

        throw new TransformationFailedException('Invalid value.');
    }

}