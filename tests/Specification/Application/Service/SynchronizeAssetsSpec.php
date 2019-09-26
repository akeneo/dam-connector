<?php
declare(strict_types=1);

namespace Specification\AkeneoDAMConnector\Application\Service;

use AkeneoDAMConnector\Application\DamAdapter\FetchAssets;
use AkeneoDAMConnector\Application\Mapping\AssetTransformer;
use AkeneoDAMConnector\Application\PimAdapter\UpdateAsset;
use AkeneoDAMConnector\Application\Service\SynchronizeAssets;
use AkeneoDAMConnector\Domain\AssetFamilyCode;
use AkeneoDAMConnector\Tests\Specification\Builder\PimAssetBuilder;
use PhpSpec\ObjectBehavior;
use AkeneoDAMConnector\Tests\Specification\Builder\DamAssetBuilder;

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
        $assetFamilyCode = new AssetFamilyCode('packshot');
        $damTable = DamAssetBuilder::build('table', 'packshot');
        $damMug = DamAssetBuilder::build('mug', 'packshot');
        $pimTable = PimAssetBuilder::build('table', 'packshot');
        $pimMug = PimAssetBuilder::build('mug', 'packshot');

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
