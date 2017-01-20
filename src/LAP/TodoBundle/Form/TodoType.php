<?php

namespace LAP\TodoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

class TodoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->add('titre',			TextType::class)
			->add('auteur',			TextType::class)
			->add('isdone',			IntegerType::class)
			->add('dateCrea',		DateType::class)
			->add('dateFin',		DateType::class, array(
				'widget' => 'single_text',
				'html5' => false,
				'attr' => ['class' => 'form-control datepicker1']
			))
			->add('forall',			ChoiceType::class, array(
				'choices' => array(
					'Oui'		=> 1,
					'Non'		=> 0
				)
			))
			->add('Valider',		SubmitType::class, array(
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
            'data_class' => 'LAP\TodoBundle\Entity\Todolist'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lap_todobundle_todolist';
    }


}
