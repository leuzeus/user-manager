<?php

namespace App\Application\UseCase;

use App\Domain\Entity\PricingOption;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\Repository\PricingOptionRepositoryInterface;
use InvalidArgumentException;

class AddPricingOption
{
    public function __construct(
        private ProductRepositoryInterface $products,
        private PricingOptionRepositoryInterface $pricingOptions
    ) {}

    /**
     * Add a pricing option for a product.
     *
     * @param int $productId
     * @param string $name
     * @param int $duration
     * @param float $price
     * @param string $currency
     * @return PricingOption
     * @throws InvalidArgumentException If product doesn't exist
     */
    public function handle(
        int $productId,
        string $name,
        int $duration,
        float $price,
        string $currency = 'USD'
    ): PricingOption {
        $product = $this->products->getById($productId);

        if (!$product) {
            throw new InvalidArgumentException("Product #$productId not found.");
        }

        return $this->pricingOptions->add($product, $name, $duration, $price, $currency);
    }
}
