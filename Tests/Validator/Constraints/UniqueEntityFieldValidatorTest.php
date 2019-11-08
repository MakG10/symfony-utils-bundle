<?php

namespace MakG\SymfonyUtilsBundle\Validator\Constraints;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class UniqueEntityFieldValidatorTest extends ConstraintValidatorTestCase
{

    public function testDuplicatedField()
    {
        $constraint = new UniqueEntityField(
            [
                'entity' => 'App\Entity\User',
                'field' => 'email',
            ]
        );

        $repository = $this->createMock(EntityRepository::class);
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'test@example.org'])
            ->willReturn(new \stdClass());

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with($constraint->entity)
            ->willReturn($repository);

        $this->validator = new UniqueEntityFieldValidator($entityManager);
        $this->validator->initialize($this->context);
        $this->validator->validate('test@example.org', $constraint);

        $this->buildViolation($constraint->message)
            ->setCode(UniqueEntityField::DUPLICATED_VALUE_ERROR)
            ->assertRaised();
    }

    protected function createValidator()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        return new UniqueEntityFieldValidator($entityManager);
    }
}
