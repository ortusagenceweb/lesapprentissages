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
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->add('active',	ChoiceType::class, array(
				'choices' => array(
					'Oui'		=> 1,
					'Non'		=> 0
				)
			))
			->add('roles',		EntityType::class, array(
				'class'			=>  'LAPUtilisateurBundle:Adminroles',
				'choice_label'	=> 'name',
				'multiple'		=> false,
			))
			->add('username',	TextType::class)
			->add('name',		TextType::class)
			->add('password',	PasswordType::class)
			->add('surname',	TextType::class)
			->add('picture',	TextType::class)
			->add('datecrea',	DateType::class, array(
				'widget' => 'single_text',
				'html5' => false,
				'attr' => ['class' => 'form-control datepicker1']
			))
			->add('datebirth',	DateType::class, array(
				'widget' => 'single_text',
				'html5' => false,
				'attr' => ['class' => 'form-control datepicker1']
			))
			->add('email',		TextType::class)
			->add('adress',		TextType::class)
			->add('postalcode',	IntegerType::class)
			->add('city',		TextType::class)
			->add('phone',		TextType::class)
			->add('country',	TextType::class)
			->add('presentation', 'Ivory\CKEditorBundle\Form\Type\CKEditorType', array(
				'config' => array(
					'uiColor' => '#ffffff'
				),
			))
			->add('onsite',	ChoiceType::class, array(
				'choices' => array(
					'Oui'		=> 1,
					'Non'		=> 0
				)
			))
			->add('Valider',	SubmitType::class, array(
				'attr' => ['class' => 'btn btn-success']
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
