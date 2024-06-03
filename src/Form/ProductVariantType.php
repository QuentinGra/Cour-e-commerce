<?php

namespace App\Form;

use App\Entity\Taxe;
use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Repository\ProductRepository;
use Doctrine\ORM\QueryBuilder;
use App\Repository\TaxeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProductVariantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('price', NumberType::class, [
                'label' => 'Prix',
            ])
            ->add('size', NumberType::class, [
                'label' => 'Size',
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'query_builder' => function (ProductRepository $pr): QueryBuilder {
                    return $pr->createQueryBuilder('p')
                        ->andWhere('p.enable = :enable')
                        ->setParameter('enable', true);
                },
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('taxe', EntityType::class, [
                'class' => Taxe::class,
                'query_builder' => function (TaxeRepository $tr): QueryBuilder {
                    return $tr->createQueryBuilder('t')
                        ->andWhere('t.enable = :enable')
                        ->setParameter('enable', true);
                },
                'choice_label' => 'rate',
                'expanded' => false,
                'multiple' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductVariant::class,
            'sanitize_html' => true,
        ]);
    }
}
