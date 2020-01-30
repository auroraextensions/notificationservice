<?php
/**
 * XmlConverter.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/notificationoutbox/LICENSE.txt
 *
 * @package       AuroraExtensions_NotificationOutbox
 * @copyright     Copyright (C) 2020 Aurora Extensions <support@auroraextensions.com>
 * @license       MIT License
 */
declare(strict_types=1);

namespace AuroraExtensions\NotificationOutbox\Model\Config;

use DOMDocument, DOMElement, DOMNode;
use Magento\Framework\{
    Config\ConverterInterface,
    Stdlib\BooleanUtils
};

class XmlConverter implements ConverterInterface
{
    /** @property BooleanUtils $booleanUtils */
    protected $booleanUtils;

    /**
     * @param BooleanUtils $booleanUtils
     * @return void
     */
    public function __construct(
        BooleanUtils $booleanUtils
    ) {
        $this->booleanUtils = $booleanUtils;
    }

    /**
     * @param DOMDocument $source
     * @return array
     */
    public function convert($source)
    {
        /** @var array $result */
        $result = [];

        /** @var DOMNodeList $releaseNodes */
        $releaseNodes = $source->documentElement->childNodes;

        /** @var DOMNode $releaseNode */
        foreach ($releaseNodes as $releaseNode) {
            if ($releaseNode->nodeType === XML_ELEMENT_NODE) {
                /** @var string $groupType */
                $groupType = $releaseNode->attributes
                    ->getNamedItem('group')
                    ->nodeValue;

                /** @var string $version */
                $version = $releaseNode->attributes
                    ->getNamedItem('version')
                    ->nodeValue;

                /** @var DOMElement[] $childNodes */
                $childNodes = $this->getChildNodesByTagName(
                    $releaseNode,
                    'notifications'
                );

                if (!empty($childNodes)) {
                    /** @var DOMElement $notifsNode */
                    $notifsNode = $childNodes[0];

                    /** @var DOMNode $notifNode */
                    foreach ($notifsNode->childNodes as $notifNode) {
                        if ($notifNode->nodeType === XML_ELEMENT_NODE) {
                            /** @var int|string $indexValue */
                            $indexValue = $notifNode->attributes
                                ->getNamedItem('index')
                                ->nodeValue;

                            /** @var string|null $severity */
                            $severity = $notifNode->attributes
                                ->getNamedItem('severity')
                                ->nodeValue;

                            $result[$groupType][$version][$indexValue] = [
                                'index' => $indexValue,
                                'severity' => $severity,
                            ];

                            /** @var DOMNode $dataNode */
                            foreach ($notifNode->childNodes as $dataNode) {
                                if ($dataNode->nodeType === XML_ELEMENT_NODE) {
                                    /** @var string $nodeName */
                                    $nodeName = $dataNode->nodeName;

                                    /** @var string $nodeValue */
                                    $nodeValue = $dataNode->nodeValue;

                                    /** @var string $translateValue */
                                    $translateValue = $dataNode->attributes
                                        ->getNamedItem('translate')
                                        ->nodeValue;

                                    /** @var bool $isTranslatable */
                                    $isTranslatable = $this->booleanUtils
                                        ->toBoolean($translateValue);

                                    /** @var Phrase|string $textValue */
                                    $textValue = $isTranslatable ? __($nodeValue) : $nodeValue;

                                    $result[$groupType][$version][$indexValue] += [
                                        $nodeName => $textValue,
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param DOMElement $element
     * @param string $tagName
     * @return array
     */
    private function getChildNodesByTagName(
        DOMElement $element,
        string $tagName
    ): array
    {
        /** @var array $result */
        $result = [];

        /** @var DOMNode $node */
        foreach ($element->childNodes as $node) {
            if ($node instanceof DOMElement && $node->tagName === $tagName) {
                $result[] = $node;
            }
        }

        return $result;
    }
}
