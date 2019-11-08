<?php

namespace MakG\SymfonyUtilsBundle\Validator\Constraints;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Checks value uniqueness against Doctrine entity.
 */
class UniqueEntityFieldValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueEntityField) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\UniqueEntityField');
        }

        if (null === $constraint->entity || null === $constraint->field) {
            throw new \Exception('"entity" and "field" params are required for UniqueEntityField constraint.');
        }

        $repository = $this->entityManager->getRepository($constraint->entity);
        $existingRecord = $repository->findOneBy([$constraint->field => $value]);

        if (null !== $existingRecord) {
            $this->context->buildViolation($constraint->message)
                ->setCode(UniqueEntityField::DUPLICATED_VALUE_ERROR)
                ->addViolation();
        }
    }
}