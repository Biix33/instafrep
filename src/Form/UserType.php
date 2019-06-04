<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'nom',
            ])
            ->add('pseudo', null, [
                'label' => 'Pseudo',
            ])
            ->add('birthday', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
            ])
//            ->add('password', PasswordType::class, [
//                'label' => 'Mot de passe',
//                'required' => false,
//            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail'
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer mon compte',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
