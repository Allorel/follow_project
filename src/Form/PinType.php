<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('imageFile', VichImageType::class, [
            'label'=>'Soumettez la photo de votre projet',
            'required' => false,
            'allow_delete' => true,
            'delete_label' => 'Suprimer  image',
            'download_label' => 'Télécharger image',
            'download_uri' => true,
            'image_uri' => true,
            'asset_helper' => true,
            'imagine_pattern' => '',
        ])
            ->add('title')
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pin::class,
        ]);
    }
}
