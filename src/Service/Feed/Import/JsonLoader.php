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

    public function load(): Collection
    {
       foreach ( Finder::findFiles(self::FILE_CATEGORY_FILE_MASK)->in($this->directory) as $file) {
           $data = json_decode($file->read(), true);
           $this->loadMainCategory($data['vehicle'], $data['categories']);
           dump(array_keys($data));
       }

        foreach ( Finder::findFiles('*.json')->exclude(self::FILE_CATEGORY_FILE_MASK)->in($this->directory) as $file) {
            if ($file->getFilename() === self::FILE_VEHICLES) {
                $data = json_decode($file->read(), true);
                dump($data);
            }
        }

       return $this->categories;
    }

    protected function loadProduct($data): void
    {
    }

    protected function loadMainCategory(array $vehicle, array $categories): void
    {
        $parts = explode('/', $vehicle['name']);
        $nameLevel1 = $parts[0];

        if (!$this->categories->offsetExists($nameLevel1)) {
            $main = new Category($nameLevel1);
            $this->categories->set($nameLevel1, $main);
        } else {
            $main = $this->categories->offsetGet($nameLevel1);
        }

        //dump($categories);

        if (isset($parts[1])) {
            $nameLevel2 = $parts[1];
            if (!$this->categories->offsetExists($nameLevel2)) {
                $this->categories->set($nameLevel2, new Category($nameLevel2, $this->categories->offsetGet($nameLevel1)));
            }
            $subcategory = new Category($nameLevel2, $main);

            $main->addChild($subcategory);
        }
    }
}