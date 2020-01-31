<?php
/**
 * NotificationSearchResultsInterface.php
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

namespace AuroraExtensions\NotificationService\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface NotificationSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \AuroraExtensions\NotificationService\Api\Data\NotificationInterface[]
     */
    public function getItems();

    /**
     * @param \AuroraExtensions\NotificationService\Api\Data\NotificationInterface[] $items
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function setItems(array $items);
}
