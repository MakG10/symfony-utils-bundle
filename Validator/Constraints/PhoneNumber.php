<?php

namespace MakG\SymfonyUtilsBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PhoneNumber extends Constraint
{
    public $message = 'Invalid phone number.';
}
