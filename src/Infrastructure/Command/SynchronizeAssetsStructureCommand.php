<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Command;

use AkeneoDAMConnector\Application\Service\SynchronizeAssetsStructure;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class SynchronizeAssetsStructureCommand extends Command
{
    const NAME = 'dam-connector:assets:synchronize-structure';
    private $synchronizeAssetsStructure;

    public function __construct(SynchronizeAssetsStructure $synchronizeAssetsStructure)
    {
        parent::__construct(self::NAME);

        $this->synchronizeAssetsStructure = $synchronizeAssetsStructure;
    }

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Synchronize assets structure between middleware configuration and Akeneo PIM');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Synchronizing assets structure</info>');

        try {
            $this->synchronizeAssetsStructure->execute();
            $output->writeln('<info>Assets structure synchronized!!!</info>');
        } catch (\Exception $e) {
            $output->writeln('<error>'. $e->getMessage() .'</error>');
        }
    }
}
