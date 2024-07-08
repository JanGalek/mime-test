<?php

declare(strict_types=1);

namespace Mime\Model\Collection;

use JsonSerializable;
use Mime\Model\Category;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @template ArrayCollection<Category>
 */
class CategoryCollection extends ArrayCollection implements JsonSerializable
{

    public function jsonSerialize(): array
    {
        $result = [];

        //foreach ($this->toArray())

        return $this->toArray();
    }
}