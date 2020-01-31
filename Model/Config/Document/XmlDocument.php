<?php
/**
 * XmlDocument.php
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

namespace AuroraExtensions\NotificationService\Model\Config\Document;

use DOMDocument, DOMElement;
use AuroraExtensions\NotificationService\{
    Component\Config\Document\XmlDocumentTrait,
    Csi\Config\Document\XmlDocumentInterface
};

class XmlDocument implements XmlDocumentInterface
{
    /**
     * @property DOMDocument $document
     * @method DOMElement[] getChildNodes()
     * @method DOMElement[] getChildNodesByTagName()
     */
    use XmlDocumentTrait;

    /** @constant string ROOT_NODE */
    public const ROOT_NODE = '<config></config>';

    /**
     * @param string $xml
     * @return void
     */
    public function __construct(
        string $xml = self::ROOT_NODE
    ) {
        $this->document = new DOMDocument();
        $this->document->loadXML($xml);
    }
}
