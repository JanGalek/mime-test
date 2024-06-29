<?php

declare(strict_types=1);

namespace Mime\Service\Feed\Import;

use Mime\Model\Category;
use Mime\Model\Product;
use Mime\Service\Feed\Import\FileLoader;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;

class JsonLoader implements FileLoader
{
    private array $mainCategories = [];
    private array $subMainCategories = [];
    private array $products;

    public function __construct(private string $directory)
    {
    }

    public function load()
    {
        $mainCategories = [];

       foreach ( Finder::findFiles('[0-9]**.json')->in($this->directory) as $file) {
           $data = json_decode($file->read(), true);
           if (!isset($data['vehicle'])) {
               bdump($file);
               bdump($data);
               continue;
           }
           $this->loadMainCategory($data['vehicle']);
       }

       bdump($this->mainCategories);
    }

    protected function loadProduct($data): void
    {
    }

    protected function loadMainCategory($data): void
    {
        $parts = explode('/', $data['name']);
        $nameLevel1 = $parts[0];
        $nameLevel2 = $parts[1];

        if (!isset($this->mainCategories[$nameLevel1])) {
            $this->mainCategories[$nameLevel1] = new Category($nameLevel1);
        }

        if (!isset($this->subMainCategories[$nameLevel2])) {
            $this->subMainCategories[$nameLevel2] = new Category($nameLevel2, $this->mainCategories[$nameLevel1]);
        }
    }
}