<?php

namespace App\Form\Type;

use App\Entity\Event;
use App\Form\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    /**
     * @var DateTimeToStringTransformer
     */
    private DateTimeToStringTransformer $transformer;

    public function __construct(DateTimeToStringTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'date',
                TextType::class, [
                    'label' => 'Date',
                    'attr'  => [
                        'class'       => 'js-datetime-picker datetimepicker-input',
                        'data-toggle' => 'datetimepicker',
                        'data-target' => '.js-datetime-picker',
                    ],
                ]
            )
            ->add('isDateOnly', CheckboxType::class, ['required' => false, 'label' => 'N\'afficher que la date, sans l\'heure'])
            ->add('label', TextType::class, ['label' => 'Titre'])
            ->add('description', TextareaType::class, ['required' => false, 'label' => 'Description'])
            ->add('private', CheckboxType::class, ['required' => false, 'label' => 'Privé ? *']);
        $builder->get('date')->addModelTransformer($this->transformer);
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
