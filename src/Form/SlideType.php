<?php
namespace App\Form;

use App\Entity\Media;
use App\Entity\Slide;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Contracts\Translation\TranslatorInterface;

class SlideType extends AbstractType
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
            ->add('extrait', TextType::class, [
                'label' => $this->translator->trans('form.description'),
                "attr" => [
                    "maxlength" => "200", 
                ]
            ])
            ->add('lien', TextType::class,[
                'label'=> $this->translator->trans('form.link')
                ])
            ->add('button', TextType::class, [
                'label' => $this->translator->trans('form.slide.button-text'),
                'attr' => [
                    "maxlength" => "200", 
                ]
            ])
            ->add('active', null, [
                'label' => $this->translator->trans('form.slide.main')
            ])
            ->add('textDark')
            ->add('bgDark')
            ->add('media', EntityType::class, [
                'label' =>  $this->translator->trans('form.slide.image'),
                'choice_label' => 'title',
                'class' => Media::class,
                'expanded' => true,
                'multiple' => false
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
            'data_class' => Slide::class,
        ]);
    }
}
