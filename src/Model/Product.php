<?php

declare(strict_types=1);

namespace Mime\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

class Product implements JsonSerializable
{
    private Collection $categories;

    public function __construct(
        private string $name,
        private string $number,
        private int $vatPercentage,
        private float $priceWithVat = 0,
    ) {
        $this->categories = new ArrayCollection();
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setCategories(Collection $categories): void
    {
        $this->categories = $categories;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getVatPercentage(): int
    {
        return $this->vatPercentage;
    }

    public function setVatPercentage(int $vatPercentage): void
    {
        $this->vatPercentage = $vatPercentage;
    }

    public function getPriceWithVat(): float
    {
        return $this->priceWithVat;
    }

    public function setPriceWithVat(float $priceWithVat): void
    {
        $this->priceWithVat = $priceWithVat;
    }

    public function getVatPercentageCoefficient(): int
    {
        return $this->vatPercentage + 100;
    }

    /**
     * 121 ...... 121%
     * x ........ 100%
     */
    public function getPriceWithoutVat()
    {
        if ($this->priceWithVat === 0) {
            return 0;
        }

        return $this->priceWithVat * ($this->getVatPercentageCoefficient() / 100 );
    }


    public function existsCategory(Category $category): bool
    {
        return $this->categories->contains($category);
    }

    public function addCategory(Category $category): self
    {
        if (!$this->existsCategory($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'number' => $this->number,
            'name' => $this->name,
            'price' => [
                'withVat' => $this->getPriceWithVat(),
                'withoutVat' => $this->getPriceWithoutVat(),
                'vatPercentage' => $this->vatPercentage,
            ],
            'categories' => $this->categories->toArray(),
        ];
    }
}