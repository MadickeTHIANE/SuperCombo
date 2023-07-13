<?php
namespace App\Form;

use App\Entity\BlogBillet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlogBilletType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator){
    $this->translator = $translator;
}
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                "label" => $this->translator->trans('form.topic'),
            ])
            ->add('contenu', TextareaType::class, [
                'label' => $this->translator->trans('form.content')
            ])
            ->add('valider', SubmitType::class, [
                "label" => $this->translator->trans('form.submit'),
                "attr" => [
                    'style' => 'margin-top : 5px',
                    'class' => 'btn btn-success'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BlogBillet::class,
        ]);
    }
}
