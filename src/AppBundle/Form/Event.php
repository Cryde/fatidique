<?php

namespace AppBundle\Form;

use AppBundle\Form\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Event extends AbstractType
{
    /**
     * @var DateTimeToStringTransformer
     */
    private $dateTimeToStringTransformer;

    public function __construct(DateTimeToStringTransformer $dateTimeToStringTransformer)
    {
        $this->dateTimeToStringTransformer = $dateTimeToStringTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'date',
                TextType::class,
                [
                    'attr' =>
                        [
                            'class'       => 'js-datetime-picker datetimepicker-input',
                            'data-toggle' => 'datetimepicker',
                            'data-target' => '.js-datetime-picker',
                        ],
                ]
            )
            ->add('label', TextType::class)
            ->add('description', TextareaType::class, ['required' => false])
            ->add('private');

        $builder->get('date')->addModelTransformer($this->dateTimeToStringTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => \AppBundle\Entity\Event::class,
            ]
        );
    }
}