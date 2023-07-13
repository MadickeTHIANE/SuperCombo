<?php
namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Contracts\Translation\TranslatorInterface;

class VideoType extends AbstractType
{
    private $translator;
    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => $this->translator->trans('form.title'),
                'attr' => [
                    "maxlength" => "22"
                ]
            ])
            ->add('iframe')
            ->add('extrait', TextType::class, [
                'label' => $this->translator->trans('form.description'),
                "required" => false,
                "attr" => [
                    "maxlength" => "44", 
                ]
            ])
            ->add('Valider', SubmitType::class, [
                'label' => $this->translator->trans('form.submit'),
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
