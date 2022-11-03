<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('GET')
            ->add('name', TextType::class, [
                'label' => 'Nazwa',
                'required' => false,
                'empty_data' => null
            ])
            ->add('min_price', TextType::class, [
                'label' => 'Cena od',
                'required' => false,
                'empty_data' => null
            ])
            ->add('max_price', TextType::class, [
                'label' => 'Cena do',
                'required' => false
            ])
            ->add('description', TextType::class, [
                'label' => 'Opis',
                'required' => false
            ])
            ->add('save',SubmitType::class, [
                'label' => 'Szukaj'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
