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

class DateRangeFilterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add($options['min_field_name'], DateFilterType::class)
            ->add($options['max_field_name'], DateFilterType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'inherit_data' => true,
        ]);
        $resolver->setRequired([
            'min_field_name', 'max_field_name',
        ]);
        $resolver->setAllowedTypes('min_field_name', 'string');
        $resolver->setAllowedTypes('max_field_name', 'string');
    }
}
