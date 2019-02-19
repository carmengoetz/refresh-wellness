<?php
namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;

/**
<<<<<<< HEAD
 * UserType short summary.
 *
 * UserType description.
=======
 * this is a form builder for a user object. generates a form that will create a user object
>>>>>>> master
 *
 * @version 1.0
 * @author cst233
 */
class UserType extends AbstractType
{
    /**
     * method that creates a form builder and adds all of the form fields to it
     * @param FormBuilderInterface $builder - form builder
     * @param array $options - the options for the form builder
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //add fields to the builder
        $builder
            ->add('email', EmailType::class, array('label' => 'Email *'))
            ->add('plainPassword', RepeatedType::class, array(
                'type'=>PasswordType::class,
                'first_options' => array('label' => 'Password *'),
                'second_options' => array('label' => 'Confirm Password *'),
            ))
            ->add('firstName', TextType::class, array('data' => '', 'label' => 'First Name *'))
            ->add('lastName', TextType::class, array('data' => '', 'label' => 'Last Name *'))
            ->add('birthDate', DateType::class, array(
                'widget' => 'single_text',
                'label' => 'Birth Date *'
            ))
            ->add('city', TextType::class, array('data' => '', 'label' => 'City *'))
            ->add('country', CountryType::class, array('label' => 'Country *'))
            //Adds the form for wellness pros
            ->add('isWellPro', CheckboxType::class, array(
            'mapped' => false,
                'label' => "I am a wellness professional",
                'required' => false,
                'attr' => array('data-toggle' => 'collapse', 'data-target' => '#wellness-hidden')
            ))
            ->add('wellnessPro', WellnessProfessionalType::class, array(
                'mapped' => false,
                'label' => false
            ))
            ->add('termsAccepted', CheckboxType::class, array(
                'label' => "I accept the terms of service",
                'mapped' => false,
                'constraints' => new IsTrue(),
                ))

                ;
    }

    /**
     * sets the defaults for the options
     * magic
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}