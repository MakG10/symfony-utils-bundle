<?php

namespace MakG\SymfonyUtilsBundle;


use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Type;
use MakG\SymfonyUtilsBundle\Doctrine\Type\CurrencyType;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymfonyUtilsBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        parent::boot();

        try {
            Type::addType(CurrencyType::NAME, CurrencyType::class);
        } catch (DBALException $exception) {
        }
    }
}
