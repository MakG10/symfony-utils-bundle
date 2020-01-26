<?php

namespace MakG\SymfonyUtilsBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PhoneNumberValidator extends ConstraintValidator
{
    private const REGEX = '/^(?:1[2-8]|2[2-69]|3[2-49]|4[1-68]|5[0-9]|6[0-35-9]|[7-8][1-9]|9[145])\d{7}$/';

    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof PhoneNumber) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\PhoneNumber');
        }

        if (empty($value)) {
            return;
        }

        if (!preg_match(self::REGEX, $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();

            return;
        }
    }
}
