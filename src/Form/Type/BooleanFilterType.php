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

namespace Wucdbm\Bundle\WucdbmFilterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wucdbm\Bundle\WucdbmFilterBundle\Form\DataTransformer\NullableBooleanToStringTransformer;

class BooleanFilterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder->setData($options['data'] ?? null);
        $builder->addViewTransformer(new NullableBooleanToStringTransformer($options['value'], $options['false_values']));
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'value' => '1',
            'empty_data' => null,
            'compound' => false,
            'false_values' => ['false'],
        ]);

        $resolver->setAllowedTypes('false_values', 'array');
    }

    public function getBlockPrefix(): string {
        return 'boolean';
    }
}
