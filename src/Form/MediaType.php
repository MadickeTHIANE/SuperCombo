<?php
namespace App\Form;

use App\Entity\Media;
use App\Entity\Article;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\File;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('src', FileType::class,[
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => [
                        'image/png',
                        'image/jpeg',
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger une image au format PNG ou JPEG',
                ])
            ],
        ])
        ->add('alt')
        ->add('title')
        ->add('article', EntityType::class, [
            'label' => 'Article',
            'required' => false,
            'choice_label' => 'titre',
            'class' => Article::class,
            'expanded' => true,
            'multiple' => false,
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
            'data_class' => Media::class,
        ]);
    }
}
