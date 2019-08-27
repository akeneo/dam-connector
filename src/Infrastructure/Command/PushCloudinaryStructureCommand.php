<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Infrastructure\Command;

use AkeneoDAMConnector\Infrastructure\Pim\AssetFamilyApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PushCloudinaryStructureCommand extends Command
{
    /** @var AssetFamilyApi */
    private $assetFamilyApi;

    protected function configure()
    {
        $this
            ->setName('cloudinary-connector:structure:push')
            ->setDescription('Pushes structure from Cloudinary DAM to Akeneo PIM Asset Manager.');
    }

    public function __construct(AssetFamilyApi $assetFamilyApi)
    {
        $this->assetFamilyApi = $assetFamilyApi;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('it Works !');

        $families = $this->assetFamilyApi->listAssetFamilies();
        foreach ($families as $family) {
            var_dump($family);
        }
    }
}
