<?php
declare(strict_types=1);

namespace Specification\AkeneoDAMConnector\Application\Service;

use AkeneoDAMConnector\Application\DamAdapter\FetchAssets;
use AkeneoDAMConnector\Application\Mapping\AssetTransformer;
use AkeneoDAMConnector\Application\PimAdapter\UpdateAsset;
use AkeneoDAMConnector\Application\Service\SynchronizeAssets;
use AkeneoDAMConnector\Domain\Asset\DamAsset;
use AkeneoDAMConnector\Domain\Asset\PimAsset;
use AkeneoDAMConnector\Domain\AssetFamilyCode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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
        DamAsset $damTable,
        DamAsset $damMug,
        PimAsset $pimTable,
        PimAsset $pimMug,
        \Iterator $damAssets,
        AssetFamilyCode $assetFamilyCode
    ) {
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
