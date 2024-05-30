<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Test@exemple.com',
                ],
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre',
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Test12345!',
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'max' => 4096,
                        ]),
                        new Regex([
                            'pattern' => '/^.*(?=.{8,120})(?!.*\s)(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\-\=\¡\£\_\+\`\~\.\,\<\>\/\?\;\:\'\"\\\|\[\]\{\}]).*$/',
                            'message' => 'Votre mot de passe doit contenir plus de 8 caractères avec 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spéciale',
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmation',
                    'attr' => [
                        'placeholder' => 'Test12345!',
                    ],
                ],
                'mapped' => false,
            ]);

        if ($options['isAdmin']) {
            $builder
                ->remove('password')
                ->add('roles', ChoiceType::class, [
                    'label' => 'Roles',
                    'choices' => [
                        'Utilisateur' => 'ROLE_USER',
                        'Administrateur' => 'ROLE_ADMIN',
                    ],
                    'expanded' => true,
                    'multiple' => true,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'sanitize_html' => true,
            'isAdmin' => false,
        ]);
    }
}
