<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain;

/**
 * @author Willy Mesnage <willy.mesnage@akeneo.com>
 */
class Options
{
    /** @var []string */
    private $options;

    /** @var AssetFamilyCode */
    private $familyCode;

    /** @var AssetAttributeCode */
    private $attributeCode;

    public function __construct(AssetFamilyCode $familyCode, AssetAttributeCode $attributeCode, array $options = [])
    {
        $this->validate($options);
        $this->options = $options;
        $this->familyCode = $familyCode;
        $this->attributeCode = $attributeCode;
    }

    public function addOption(string $option): void
    {
        if (in_array($option, $this->options)) {
            return;
        }

        $this->options[] = $option;
    }

    public function merge(Options $options): void
    {
        foreach ($options->getOptions() as $option) {
            $this->addOption($option);
        }
    }

    /**
     * @param Options $options
     *
     * @return false|Options
     */
    public function getDiff(Options $options)
    {
        if ((string) $options->getFamilyCode() !== (string) $this->getFamilyCode() ||
            (string) $options->getAttributeCode() !== (string) $this->getAttributeCode()
        ) {
            return false;
        }

        $diff = array_diff($options->getOptions(), $this->getOptions());

        return new Options($this->getFamilyCode(), $this->getAttributeCode(), $diff);
    }

    public function normalize(): array
    {
        return array_reduce(
            $this->options,
            function ($carry, $option) {
                $carry[] = ['code' => $option];

                return $carry;
            },
            []
        );
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function count(): int
    {
        return count($this->options);
    }

    public function getFamilyCode(): AssetFamilyCode
    {
        return $this->familyCode;
    }

    public function getAttributeCode(): AssetAttributeCode
    {
        return $this->attributeCode;
    }

    private function validate(array $options): void
    {
        if (!empty($options)) {
            array_walk($options, function ($code) {
                if (!is_string($code)) {
                    throw new \Exception(
                        sprintf('Options must be of type string. Type "%s" given.', gettype($code))
                    );
                }
            });
        }
    }
}
