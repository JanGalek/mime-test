<?php

declare(strict_types=1);

namespace Mime\Command\Feed;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mime\Service\Feed\Import\JsonLoader;

#[AsCommand(
    name: 'feed:import'
)]
class ImportCommand extends Command
{
    public function __construct(
        private JsonLoader $loader,
        ?string $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->loader->load();

        return 0;
    }
}