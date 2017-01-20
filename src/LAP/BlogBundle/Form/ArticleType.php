<?php

namespace LAP\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('titre',			TextType::class)
			->add('auteur',			TextType::class)
			->add('idCategorie',	ChoiceType::class, array(
				'choices' => array(
					'Général'			=> 1,
					'Spécifique'		=> 2,
					'Autre'				=> 3
				),
				'label' => 'Actif ?'
			))
			->add('dateCrea',		DateType::class, array(
				'widget' => 'single_text',
				'html5' => false,
				'attr' => ['class' => 'form-control datepicker1']
			))
			->add('datePub',		DateType::class, array(
				'widget' => 'single_text',
				'html5' => false,
				'attr' => ['class' => 'form-control datepicker1']
			))
			->add('active',			ChoiceType::class, array(
				'choices' => array(
					'Oui'			=> 1,
					'Non'		=> 0
				),
				'label' => 'Actif ?'
			))
			->add('texte', 			'Ivory\CKEditorBundle\Form\Type\CKEditorType', array(
				'config' => array(
					'uiColor' => '#ffffff'
				),
			))
			->add('Valider',		SubmitType::class, array(
				'attr' => ['class' => 'btn btn-success descend-10']
			))
		;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LAP\BlogBundle\Entity\Article'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lap_blogbundle_article';
    }


}
