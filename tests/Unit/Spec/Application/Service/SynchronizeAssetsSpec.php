<?php
declare(strict_types=1);

namespace Spec\AkeneoDAMConnector\Application\Service;

use AkeneoDAMConnector\Application\DamAdapter\FetchAssets;
use AkeneoDAMConnector\Application\Mapping\AssetTransformer;
use AkeneoDAMConnector\Application\PimAdapter\UpdateAsset;
use AkeneoDAMConnector\Application\Service\SynchronizeAssets;
use AkeneoDAMConnector\Domain\Model\Dam\DamAsset;
use AkeneoDAMConnector\Domain\Model\Dam\DamAssetIdentifier;
use AkeneoDAMConnector\Domain\Model\FamilyCode;
use AkeneoDAMConnector\Domain\Model\Pim\PimAsset;
use AkeneoDAMConnector\Tests\Specification\Builder\DamAssetBuilder;
use AkeneoDAMConnector\Tests\Specification\Builder\PimAssetBuilder;
use PhpSpec\ObjectBehavior;

class SynchronizeAssetsSpec extends ObjectBehavior
{
    public function let(
        FetchAssets $fetchAssets,
        AssetTransformer $assetTransformer,
        UpdateAsset $assetApi
    ): void {
        $this->beConstructedWith($fetchAssets, $assetTransformer, $assetApi);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SynchronizeAssets::class);
    }

    function it_synchronizes_assets(
        $fetchAssets,
        $assetTransformer,
        $assetApi,
        \Iterator $damAssets
    ) {
        $assetFamilyCode = new FamilyCode('packshot');
        $damTable = new DamAsset(new DamAssetIdentifier('table'), new FamilyCode('packshot'), null);
        $damMug = new DamAsset(new DamAssetIdentifier('mug'), new FamilyCode('packshot'), null);
        $pimTable = new PimAsset('table', new FamilyCode('packshot'));
        $pimMug = new PimAsset('mug', new FamilyCode('packshot'));

        $damAssets->rewind()->shouldBeCalled();
        $damAssets->valid()->willReturn(true, true, false);
        $damAssets->current()->willReturn($damTable, $damMug);
        $damAssets->next()->shouldBeCalled();

        $fetchAssets->fetch($assetFamilyCode, null)->willReturn($damAssets);
        $assetTransformer->damToPim($damTable)->willReturn($pimTable);
        $assetTransformer->damToPim($damMug)->willReturn($pimMug);

        $assetApi->upsert($assetFamilyCode, $pimTable)->shouldBeCalled();
        $assetApi->upsert($assetFamilyCode, $pimMug)->shouldBeCalled();
        $assetApi->flush($assetFamilyCode)->shouldBeCalled();

        $this->execute($assetFamilyCode, null);
    }
}
