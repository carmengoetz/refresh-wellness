<?php
namespace AppBundle\Form;

use AppBundle\Entity\WellnessProfessional;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;
/**
 * WellnessProfessionalType short summary.
 *
 * WellnessProfessionalType description.
 *
 * @version 1.0
 * @author cst233
 */
class WellnessProfessionalType extends AbstractType
{
    //Attributes to store these fields until use
    public $practiceName;
    public $contactNumber;
    public $contactEmail;
    public $website;

    /**
     * method that creates a form builder and adds all of the form fields to it
     * @param FormBuilderInterface $builder - form builder
     * @param array $options - the options for the form builder
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //add fields to the builder
        $builder

            ->add('practiceName', TextType::class, array(
                'label' => 'Practice Name *',
                'required' => false
            ))
            ->add('contactNumber', TextType::class, array(
                'label' => 'Contact Phone',
                'required' => false
            ))
            ->add('contactEmail', EmailType::class, array(
                'label' => 'Contact Email',
                'required' => false
            ))
            ->add('website', UrlType::class, array(
                'label' => 'Website',
                'required' => false
            ));

    }

    /**
     * sets the defaults for the options
     * magic
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => WellnessProfessionalType::class,
        ));
    }
}