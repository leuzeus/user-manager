<?php

namespace App\Application\UseCase;

use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepositoryInterface;

class AddProduct
{
    public function __construct(
        private ProductRepositoryInterface $products
    ) {}

    /**
     * Add a new product.
     *
     * @param string $name
     * @param string|null $description
     * @return Product
     */
    public function handle(string $name, ?string $description = null): Product
    {
        return $this->products->add($name, $description);
    }
}
