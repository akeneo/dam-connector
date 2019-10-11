<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Command;

use AkeneoDAMConnector\Application\ConfigLoader;
use AkeneoDAMConnector\Application\Service\SynchronizeAssets;
use AkeneoDAMConnector\Domain\Model\FamilyCode;
use AkeneoDAMConnector\Infrastructure\Persistence\Execution;
use AkeneoDAMConnector\Infrastructure\Persistence\SynchronizeAssetsExecutionRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SynchronizeAssetsCommand extends Command
{
    const NAME = 'dam-connector:assets:synchronize';

    private $synchronizeAssets;
    private $mappingConfigLoader;
    private $synchronizeAssetsExecutionRepository;

    public function __construct(
        SynchronizeAssets $synchronizeAssets,
        ConfigLoader $mappingConfigLoader,
        SynchronizeAssetsExecutionRepository $synchronizeAssetsExecutionRepository
    ) {
        parent::__construct(self::NAME);

        $this->synchronizeAssets = $synchronizeAssets;
        $this->mappingConfigLoader = $mappingConfigLoader;
        $this->synchronizeAssetsExecutionRepository = $synchronizeAssetsExecutionRepository;
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Synchronize assets between DAM third party system and Akeneo PIM');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Synchronizing assets</info>');

        $familyCodes = array_keys($this->mappingConfigLoader->load());
        $lastSucceededExecutionTimes = $this->synchronizeAssetsExecutionRepository->findLastSucceededExecutionTimeForFamilyCodes(
            $familyCodes
        );

        foreach ($familyCodes as $familyCode) {
            $this->synchronizeFamily($familyCode, $lastSucceededExecutionTimes[$familyCode]);
        }
    }

    private function synchronizeFamily(string $familyCode, ?\DateTimeInterface $sinceLastSucceededExecutionTime): void
    {
        $assetFamilyCode = new FamilyCode($familyCode);

        $currentExecution = Execution::create($familyCode)->run();
        $this->synchronizeAssetsExecutionRepository->save($currentExecution);

        $this->synchronizeAssets->execute($assetFamilyCode, $sinceLastSucceededExecutionTime);

        $currentExecution->succeeded();
        $this->synchronizeAssetsExecutionRepository->save($currentExecution);
    }
}
