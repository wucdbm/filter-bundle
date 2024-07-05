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
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateFilterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $transformer = new DateTimeToStringTransformer(
            $options['input_timezone'], $options['output_timezone'], $options['format']
        );
        $builder->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'format' => 'Y-m-d',
            'input_timezone' => null,
            'output_timezone' => null,
        ]);
    }

    public function getParent(): ?string {
        return TextType::class;
    }
}
