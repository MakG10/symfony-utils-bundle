<?php

namespace MakG\SymfonyUtilsBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class UniqueEntityField extends Constraint
{
    public const DUPLICATED_VALUE_ERROR = '96977bf9-84e3-4fe6-a9a2-4b071ad64444';

    /** @var string */
    public $message = 'This value already exists.';

    /** @var string */
    public $entity;

    /** @var string */
    public $field;
}
