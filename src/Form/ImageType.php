<?php
namespace App\Form;

use App\Entity\Image;
use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('link')
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
            'data_class' => Image::class,
        ]);
    }
}
