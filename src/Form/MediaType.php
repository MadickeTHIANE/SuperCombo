<?php
namespace App\Form;

use App\Entity\Media;
use App\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Contracts\Translation\TranslatorInterface;

class MediaType extends AbstractType
{
    private $translator;
    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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
                    'mimeTypesMessage' => $this->translator->trans('form.image.file-error'),
                ])
            ],
        ])
        ->add('alt')
        ->add('title', TextType::class,[
            'label' => $this->translator->trans('form.title'),
            'attr' => [
                "maxlength" => "22"
                ]
            ])
            ->add('article', EntityType::class, [
                'label' => 'Article',
                'required' => false,
                'choice_label' => 'titre',
                'class' => Article::class,
                'expanded' => true,
                'multiple' => false,
                ])
            ->add('Valider', SubmitType::class, [
                'label' => $this->translator->trans('form.submit'),
                'attr' => [
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
