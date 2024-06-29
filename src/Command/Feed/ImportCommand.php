<?php

declare(strict_types=1);

namespace Mime\Command\Feed;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(
    name: 'feed:import'
)]
class ImportCommand extends Command
{

}