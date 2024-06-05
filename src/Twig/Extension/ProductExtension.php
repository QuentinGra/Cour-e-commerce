<?php

namespace App\Twig\Extension;

use App\Entity\Product;
use App\Entity\ProductImage;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ProductExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('main_image', [$this, 'getMainImage']),
        ];
    }

    public function getMainImage(Product $product): mixed
    {
        $mainImage = array_filter($product->getProductImages()->toArray(), function (ProductImage $productImage) {
            if ($productImage->getImageType() === 'main') {
                return $productImage;
            }
        });

        return $mainImage ? $mainImage[0] : null;
    }
}
