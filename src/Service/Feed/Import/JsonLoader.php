<?php

declare(strict_types=1);

namespace Mime\Service\Feed\Import;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Mime\Model\Category;
use Mime\Model\Product;
use Mime\Service\Feed\Import\FileLoader;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;

class JsonLoader implements FileLoader
{
    private const string FILE_VEHICLES = 'vehicles.json';
    private const string FILE_CATEGORY_FILE_MASK = '[0-9]**.json';
    private Collection $mainCategories;
    private Collection $subMainCategories;

    /**
     * @var Collection<Category>|ArrayCollection<string, Category>
     */
    private Collection $categories;
    private Collection $products;

    public function __construct(private string $directory)
    {
        $this->mainCategories = new ArrayCollection();
        $this->subMainCategories = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function load(): array
    {
       foreach ( Finder::findFiles(self::FILE_CATEGORY_FILE_MASK)->in($this->directory) as $file) {
           $data = json_decode($file->read(), true);
           unset($data['version'], $data['generated_at'], $data['alternatives']);
           $category = $this->loadMainCategory($data['vehicle'], $data['categories']);
       }

       //dump(json_encode($this->products->toArray()));

       return $this->products->toArray();
    }

    protected function validate()
    {

    }

    protected function loadProducts(Category $category, array $rawProducts): void
    {
        foreach ($rawProducts as $rawProduct) {
            $p = $rawProduct['product'];
            if (($product = $this->products->findFirst(function (int $key, Product $product) use ($p) {
                return $product->getNumber() === $p['product_no'];
            })) === null) {
                $product = new Product(
                    $p['name'],
                    $p['product_no'],
                    $p['vat_percent'],
                    $p['unit_price_incl_vat'] !== null ? $p['unit_price_incl_vat'] : 0
                );
            }

            $category->addProduct($product);

            if (!$this->products->contains($product)) {
                $this->products->add($product);
            }
        }
    }

    protected function loadSubCategories(Category $mainCategory, array $subCategories): Category
    {
        foreach ($subCategories as $subCategory) {
            $category = new Category($subCategory['name'], $mainCategory);
            $mainCategory->addChild($category);
            $products = $subCategory['spare_parts'];
            $this->loadProducts($category, $products);
            if (isset($subCategory['categories'])) {
                return $this->loadSubCategories($category, $subCategory['categories']);
            }
        }

        return $mainCategory;
    }

    protected function loadMainCategory(array $vehicle, array $categories): Category
    {
        $parts = explode('/', $vehicle['name']);
        $nameLevel1 = trim($parts[0]);

        if (!$this->categories->offsetExists($nameLevel1)) {
            $main = new Category($nameLevel1);
            $this->categories->set($nameLevel1, $main);
        } else {
            $main = $this->categories->offsetGet($nameLevel1);
        }

        if (isset($parts[1])) {
            $nameLevel2 = trim($parts[1]);
            if (!$this->categories->offsetExists($nameLevel2)) {
                $this->categories->set($nameLevel2, new Category($nameLevel2, $this->categories->offsetGet($nameLevel1)));
            }
            $subcategory = new Category($nameLevel2, $main);

            $main->addChild($subcategory);
            $this->loadSubCategories($subcategory, $categories);
            return $subcategory;
        }

        $subCategories = $this->loadSubCategories($main, $categories);
        return $main;
    }
}