<?php

/**
 * (c) Adrien PIERRARD
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

/**
 * Class UserType.
 */
class UserType extends AbstractType
{
    /**
     * Build a User form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'username',
                TextType::class, ['label' => "Nom d'utilisateur"]
            )
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les deux mots de passe doivent correspondre.',
                    'required' => true,
                    'first_options'  => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Tapez le mot de passe à nouveau'],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                ['label' => 'Adresse email']
            )
            ->add(
                'roles',
                CollectionType::class,
                [
                    'entry_type' => ChoiceType::class,
                    'entry_options' =>
                    [
                        'label' => false,
                        'choices' =>
                            [
                                'Administrateur' => 'ROLE_ADMIN',
                                'Utilisateur' => 'ROLE_USER',
                            ],
                        'multiple'        => false,
                        'expanded'        => true,
                    ],
                ]
            )
        ;
    }
}
