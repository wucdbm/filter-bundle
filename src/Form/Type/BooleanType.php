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
use Wucdbm\Bundle\WucdbmFilterBundle\Form\DataTransformer\BooleanModelTransformer;

class BooleanType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder->addModelTransformer(new BooleanModelTransformer(
            $options['true_values'],
            $options['false_values'],
            $options['null_values'],
            $options['required']
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'compound' => false,
            'true_values' => [1, '1', true, 'true', 'on'],
            // When a form is submitted, FALSE is converted to NULL
            // Which means that NULL transforms to FALSE
            // And empty string transforms to NULL
            // See Symfony\Component\Form\Form::submit()
            'false_values' => [0, '0', false, 'false', null, 'null'],
            'null_values' => ['', null, 'null'],
        ]);
    }
}
