<?php

declare(strict_types=1);

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

namespace Wucdbm\Bundle\WucdbmFilterBundle\Helper;

use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\ConstraintViolation;

class FormHelper
{
    private const REGEX = '/^(data\.)|(\\])|(\\[)|children|\.data$/';

    public static function formErrorPath(FormError $error): string
    {
        $cause = $error->getCause();

        if ($cause instanceof ConstraintViolation) {
            return preg_replace(self::REGEX, '', $cause->getPropertyPath());
        }

        return '';
    }
}
