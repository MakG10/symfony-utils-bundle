<?php

namespace MakG\SymfonyUtilsBundle\Doctrine\QueryFilter\Filter;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

class IdFilterTest extends TestCase
{
    public function testSupports()
    {
        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata
            ->method('getFieldNames')
            ->willReturn(['id', 'name', 'description']);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->method('getClassMetadata')
            ->willReturn($classMetadata);

        $queryBuilder = new QueryBuilder($entityManager);

        $filter = new IdFilter();

        $this->assertTrue($filter->supports($queryBuilder, 'id'));
    }

    public function testUnsupportedFilterName()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $queryBuilder = new QueryBuilder($entityManager);

        $filter = new IdFilter();

        $this->assertFalse($filter->supports($queryBuilder, 'unsupported'));
    }

    public function testUnsupportedEntityWithNoIdField()
    {
        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata
            ->method('getFieldNames')
            ->willReturn(['name', 'description']);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->method('getClassMetadata')
            ->willReturn($classMetadata);

        $queryBuilder = new QueryBuilder($entityManager);

        $filter = new IdFilter();

        $this->assertFalse($filter->supports($queryBuilder, 'id'));
    }

    public function testFilter()
    {
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder
            ->expects($this->once())
            ->method('getRootAliases')
            ->willReturn(['entity']);
        $queryBuilder
            ->expects($this->once())
            ->method('andWhere')
            ->with('entity.id IN (:id_1)')
            ->willReturnSelf();
        $queryBuilder
            ->expects($this->once())
            ->method('setParameter')
            ->with('id_1', [123]);

        $filter = new IdFilter();
        $filter->filter($queryBuilder, 'id', 123);
    }
}
