<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\Slide;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
            ->add('media', EntityType::class, [
                'label' => 'Image',
                'choice_label' => 'title',
                'class' => Media::class,
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
