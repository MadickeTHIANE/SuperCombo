<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\Slide;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('extrait')
            ->add('lien')
            ->add('button', null, [
                'label' => "Texte du bouton"
            ])
            ->add('active', null, [
                'label' => 'Slide principale'
            ])
            ->add('textDark')
            ->add('bgDark')
            ->add('image', EntityType::class, [
                'label' => 'Image',
                'choice_label' => 'titre',
                'class' => Image::class,
                'expanded' => true,
                'multiple' => false
            ])
            ->add('Valider', SubmitType::class, [
                "attr" => [
                    "class" => "btn btn-success",
                    "style" => "margin-top : 5px"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Slide::class,
        ]);
    }
}
