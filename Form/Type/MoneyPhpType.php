<?php

namespace MakG\SymfonyUtilsBundle\Form\Type;


use MakG\SymfonyUtilsBundle\Form\DataTransformer\MoneyToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoneyPhpType extends AbstractType
{
    private const DEFAULT_CURRENCY_CHOICES = ['EUR', 'PLN', 'USD'];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'amount',
                MoneyType::class,
                [
                    'scale' => 2,
                    'divisor' => 100,
                    'currency' => null,
                ]
            )
            ->add(
                'currency',
                ChoiceType::class,
                [
                    'choices' => $options['currency_choices'],
                ]
            )
            ->addModelTransformer(new MoneyToArrayTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'currency_choices' => array_combine(self::DEFAULT_CURRENCY_CHOICES, self::DEFAULT_CURRENCY_CHOICES),
            ]
        );
    }
}
