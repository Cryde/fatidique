<?php

namespace App\Form\Type;

use App\Entity\Event;
use App\Form\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'date',
                DateTimeType::class, [
                    'label' => 'Date',
                ]
            )
            ->add('isDateOnly', CheckboxType::class, ['required' => false, 'label' => 'N\'afficher que la date, sans l\'heure'])
            ->add('label', TextType::class, ['label' => 'Titre'])
            ->add('description', TextareaType::class, ['required' => false, 'label' => 'Description'])
            ->add('private', CheckboxType::class, ['required' => false, 'label' => 'Privé ? *']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
