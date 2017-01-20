<?php

namespace LAP\NotificationsBundle\Form;

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
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class NotificationsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->add('statut',	EntityType::class, array(
				'class'			=> 'LAPNotificationsBundle:Notificationsstatut',
				'choice_label'	=> 'status',
				'choice_value'	=> 'id',
				'query_builder'	=> function (EntityRepository $er) use ($options) {
					return $er->createQueryBuilder('u');
				}
			))
			->add('titre',			TextType::class, array(
				'attr' => array('maxlength' => 45)
			))
			->add('auteur',			IntegerType::class)
			->add('texte', 			'Ivory\CKEditorBundle\Form\Type\CKEditorType', array(
				'config' => array(
					'uiColor' => '#ffffff'
				),
			))
			->add('Envoyer',		SubmitType::class)
		;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LAP\NotificationsBundle\Entity\Notifications'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lap_notificationsbundle_notifications';
    }


}
