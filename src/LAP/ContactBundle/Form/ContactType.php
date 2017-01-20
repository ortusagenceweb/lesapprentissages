<?php

namespace LAP\ContactBundle\Form;

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

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('nom',			TextType::class)
			->add('prenom',			TextType::class)
			->add('email',			TextType::class)
			->add('telephone',		TextType::class, array('label' => 'Téléphone'))
			->add('choix',			ChoiceType::class, array(
				'choices' => array(
					'Demande de renseignements'			=> 'renseignements',
					'Inscription'						=> 'inscription',
					'Problème sur le site internet'		=> 'probleme site',
					'Partenariat'						=> 'partenariat',
					'Devenir membre'					=> 'devenir membre',
					'Autre question'					=> 'Autre',
				),
				'label' => 'Votre choix'
			))
			->add('texte',			TextareaType::class)
			->add('Envoyer',		SubmitType::class)
		;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LAP\ContactBundle\Entity\Contact'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lap_contactbundle_contact';
    }


}
