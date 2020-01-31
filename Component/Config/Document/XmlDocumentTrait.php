<?php
/**
 * XmlDocumentTrait.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/notificationservice/LICENSE.txt
 *
 * @package       AuroraExtensions_NotificationService
 * @copyright     Copyright (C) 2020 Aurora Extensions <support@auroraextensions.com>
 * @license       MIT License
 */
declare(strict_types=1);

namespace AuroraExtensions\NotificationService\Component\Config\Document;

use DOMDocument, DOMElement;

trait XmlDocumentTrait
{
    /** @property DOMDocument $document */
    private $document;

    /**
     * @return DOMDocument
     */
    public function getDocument(): DOMDocument
    {
        return $this->document;
    }

    /**
     * @return DOMElement
     */
    public function getDocumentElement(): DOMElement
    {
        return $this->getDocument()
            ->documentElement;
    }

    /**
     * @param DOMElement $element
     * @return array
     */
    public function getChildNodes(DOMElement $element): array
    {
        /** @var array $result */
        $result = [];

        /** @var DOMNode $node */
        foreach ($element->childNodes as $node) {
            if ($node instanceof DOMElement) {
                $result[] = $node;
            }
        }

        return $result;
    }

    /**
     * @param DOMElement $element
     * @param string $tagName
     * @return array
     */
    public function getChildNodesByTagName(
        DOMElement $element,
        string $tagName
    ): array
    {
        /** @var array $result */
        $result = [];

        /** @var array $nodes */
        $nodes = $this->getChildNodes($element);

        /** @var DOMNode $node */
        foreach ($nodes as $node) {
            if ($node->tagName === $tagName) {
                $result[] = $node;
            }
        }

        return $result;
    }

    /**
     * @param DOMElement $element
     * @return DOMElement
     */
    public function importNode(DOMElement $element): DOMElement
    {
        return $this->getDocument()
            ->importNode($element, true);
    }

    /**
     * @param DOMElement $element
     * @param DOMElement|null $parent
     * @return void
     */
    public function appendNode(
        DOMElement $element,
        DOMElement $parent = null
    ): void
    {
        /** @var DOMElement $parentNode */
        $parentNode = $parent ?? $this->getDocumentElement();
        $parentNode->appendChild($element);
    }
}
