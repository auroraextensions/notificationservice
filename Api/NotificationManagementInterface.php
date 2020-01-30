<?php
/**
 * NotificationManagementInterface.php
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

namespace AuroraExtensions\NotificationOutbox\Api;

interface NotificationManagementInterface
{
    /**
     * @return bool
     */
    public function hasUnsent(): bool;

    /**
     * @return int
     */
    public function markAsSent(Data\NotificationInterface $entry): int;

    /**
     * @return void
     */
    public function processUnsent(): void;
}
