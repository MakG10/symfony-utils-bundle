<?php

namespace MakG\SymfonyUtilsBundle\Form\DataTransformer;


use Money\Currency;
use Money\Money;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class MoneyToArrayTransformer implements DataTransformerInterface
{

    /**
     * {@inheritDoc}
     */
    public function transform($value)
    {
        if (null === $value) {
            return [
                'amount' => null,
                'currency' => null,
            ];
        }

        if (!$value instanceof Money) {
            throw new TransformationFailedException(sprintf('Expected %s type.', Money::class));
        }

        return [
            'amount' => $value->getAmount(),
            'currency' => $value->getCurrency()->getCode(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($value)
    {
        if (!isset($value['amount']) || !isset($value['currency'])) {
            throw new TransformationFailedException('Expected array with "amount" and "currency" keys.');
        }

        return new Money($value['amount'], new Currency($value['currency']));
    }
}
