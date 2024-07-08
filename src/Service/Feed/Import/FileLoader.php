<?php
declare(strict_types=1);

namespace Mime\Service\Feed\Import;

use Doctrine\Common\Collections\Collection;

interface FileLoader
{
    public function load(): array;
}