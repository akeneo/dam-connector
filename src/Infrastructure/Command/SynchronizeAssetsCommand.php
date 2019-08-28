<?php

declare(strict_types=1);

/*
 * This file is part of the Akeneo PIM Enterprise Edition.
 *
 * (c) 2019 Akeneo SAS (http://www.akeneo.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AkeneoDAMConnector\Infrastructure\Command;

use AkeneoDAMConnector\Application\Service\SynchronizeAssets;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Romain Monceau <romain@akeneo.com>
 */
class SynchronizeAssetsCommand extends Command
{
    const NAME = 'dam-connector:assets:synchronize';
    private $synchronizeAssets;

    public function __construct(SynchronizeAssets $synchronizeAssets)
    {
        parent::__construct(self::NAME);

        $this->synchronizeAssets = $synchronizeAssets;
    }

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Synchronize assets between DAM third party system and Akeneo PIM');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Synchronizing assets</info>');

        try {
            $this->synchronizeAssets->execute();
            $output->writeln('<info>Assets synchronized!!!</info>');
        } catch (\Exception $e) {
            $output->writeln('<error>'. $e->getMessage() .'</error>');
        }
    }
}
