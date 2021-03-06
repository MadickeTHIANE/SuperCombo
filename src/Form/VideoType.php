<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                "attr" => [
                    "maxlength" => "22"
                ]
            ])
            ->add('iframe')
            ->add('extrait', TextType::class, [
                "required" => false,
                "attr" => [
                    "maxlength" => "44", 
                ]
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
            'data_class' => Video::class,
        ]);
    }
}
