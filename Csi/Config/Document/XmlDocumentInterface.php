<?php
/**
 * XmlDocumentInterface.php
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

namespace AuroraExtensions\NotificationService\Csi\Config\Document;

use DOMDocument, DOMElement;

interface XmlDocumentInterface
{
    /**
     * @return DOMDocument
     */
    public function getDocument(): DOMDocument;

    /**
     * @return DOMElement
     */
    public function getDocumentElement(): DOMElement;

    /**
     * @param DOMElement $element
     * @return array
     */
    public function getChildNodes(DOMElement $element): array;

    /**
     * @param DOMElement $element
     * @param string $tagName
     * @return array
     */
    public function getChildNodes(DOMElement $element, string $tagName): array;

    /**
     * @param DOMElement $element
     * @return DOMElement
     */
    public function importNode(DOMElement $element): DOMElement;

    /**
     * @param DOMElement $element
     * @param DOMElement $parent
     * @return DOMElement
     */
    public function appendNode(DOMElement $element, DOMElement $parent): DOMElement;
}
