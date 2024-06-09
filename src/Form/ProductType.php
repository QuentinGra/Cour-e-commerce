<?php

namespace App\Form;

use App\Entity\Gender;
use App\Entity\Marque;
use App\Entity\Model;
use App\Entity\Product;
use App\Repository\GenderRepository;
use App\Repository\MarqueRepository;
use App\Repository\ModelRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom de votre produit',
                ],
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description de votre produit',
                ],
                'required' => true,
            ])
            ->add('authenticity', TextareaType::class, [
                'label' => 'Authenticiter',
                'attr' => [
                    'placeholder' => 'Authenticiter de votre produit',
                ],
                'required' => false,
            ])
            ->add('enable', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
            ])
            ->add('productImages', CollectionType::class, [
                'entry_type' => ProductImageType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'delete_empty' => true,
            ])
            ->add('marque', EntityType::class, [
                'class' => Marque::class,
                'query_builder' => function (MarqueRepository $maR): QueryBuilder {
                    return $maR->createQueryBuilder('ma')
                        ->andWhere('ma.enable = :enable')
                        ->setParameter('enable', true);
                },
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('model', EntityType::class, [
                'class' => Model::class,
                'query_builder' => function (ModelRepository $moR): QueryBuilder {
                    return $moR->createQueryBuilder('mo')
                        ->andWhere('mo.enable = :enable')
                        ->setParameter('enable', true);
                },
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('gender', EntityType::class, [
                'class' => Gender::class,
                'query_builder' => function (GenderRepository $gR): QueryBuilder {
                    return $gR->createQueryBuilder('g')
                        ->andWhere('g.enable = :enable')
                        ->setParameter('enable', true);
                },
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'sanitize_html' => true,
        ]);
    }
}
