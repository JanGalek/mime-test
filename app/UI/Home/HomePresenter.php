<?php

declare(strict_types=1);

namespace App\UI\Home;

use Mime\Service\Feed\Import\JsonLoader;
use Nette;
use Nette\DI\Attributes\Inject;


final class HomePresenter extends Nette\Application\UI\Presenter
{
    #[Inject]
    public JsonLoader $loader;

    public function renderDefault()
    {
        $this->sendJson($this->loader->load());
    }
}
