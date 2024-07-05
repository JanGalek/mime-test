<?php

declare(strict_types=1);

namespace Mime\Model;

use Mime\Model\Collection\CategoryCollection;

class Category
{
    private CategoryCollection $children;

    public function __construct(
        private string $name,
        private ?Category $parent = null,
    ) {
        $this->children = new CategoryCollection();
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

}