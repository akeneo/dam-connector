<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SynchronizeAssetsCommand extends Command
{
    const NAME = 'pim-api-test:fetch:product';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Synchronize assets between DAM third party system and Akeneo PIM');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
