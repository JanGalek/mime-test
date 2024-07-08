<?php

declare(strict_types=1);

namespace Mime\Model;

use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
use Mime\Model\Collection\CategoryCollection;

class Category implements JsonSerializable
{
    private CategoryCollection $children;

    /** @var ArrayCollection<int, Product> */
    private ArrayCollection $products;

    public function __construct(
        private string $name,
        private ?Category $parent = null,
    ) {
        $this->children = new CategoryCollection();
        $this->products = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getParent(): ?Category
    {
        return $this->parent;
    }

    public function setParent(?Category $parent): void
    {
        $this->parent = $parent;
    }

    public function getChildren(): CategoryCollection
    {
        return $this->children;
    }

    public function setChildren(CategoryCollection $children): void
    {
        $this->children = $children;
    }

    public function getChild(string $name): ?Category
    {
        return $this->children->offsetGet($name);
    }

    public function addChild(Category $category): self
    {
        if (!$this->getChild($category->getName())) {
            $this->children->add($category);
        }
        return $this;
    }

    public function getProduct(string $number): ?Product
    {
        return $this->products->findFirst(function (int $key, Product $product) use ($number) {
            return $product->getNumber() === $number;
        });
    }

    public function addProduct(Product $product): self
    {
        if ($this->getProduct($product->getNumber()) === null) {
            $this->products->add($product);
            $product->addCategory($this);
        }

        return $this;
    }

    public function getProducts(): ArrayCollection
    {
        return $this->products;
    }

    public function setProducts(ArrayCollection $products): void
    {
        $this->products = $products;
    }


    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'parent' => $this->parent?->jsonSerialize(),
        ];
    }
}