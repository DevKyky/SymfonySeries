<?php

namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\ImageValidator;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('overview', TextareaType::class, [
                'required' => false,
                'label' => 'Description'
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Canceled' => 'canceled',
                    'Returning' => 'returning',
                    'Ended' => 'ended'
                ]
            ])
            ->add('vote', NumberType::class)
            ->add('popularity', NumberType::class)
            ->add('genres', ChoiceType::class, [
                'choices' => [
                    'Drama' => 'drama',
                    'SF' => 'sf',
                    'Western' => 'western'
                ],
                'expanded' => true,
                'multiple' => true,
                'mapped' => false
            ])
            ->add('firstAirDate', DateType::class, [
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('lastAirDate', DateType::class, [
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('backdrop', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '7000k',
                        'maxSizeMessage' => 'The file is too big'
                    ])
                ]
            ])
            ->add('poster', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '7000k',
                        'maxSizeMessage' => 'The file is too big'
                    ])
                ]
            ])
            ->add('tmdbId');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }
}
