<?php

namespace LAP\UtilisateurBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsereditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('username',	TextType::class)
			->add('name',		TextType::class)
			->add('surname',	TextType::class)
			->add('datebirth',		DateType::class, array(
				'widget' => 'single_text',
				'html5' => false,
				'attr' => ['class' => 'form-control datepicker1']
			))
			->add('adress',		TextType::class)
			->add('postalcode',	IntegerType::class)
			->add('city',		TextType::class)
			->add('phone',		TextType::class)
			->add('country',	TextType::class)
			->add('Envoyer',	SubmitType::class, array(
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
            'data_class' => 'LAP\UtilisateurBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lap_utilisateurbundle_user';
    }


}
