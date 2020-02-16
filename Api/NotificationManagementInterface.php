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
 * https://docs.auroraextensions.com/magento/extensions/2.x/notificationservice/LICENSE.txt
 *
 * @package       AuroraExtensions_NotificationService
 * @copyright     Copyright (C) 2020 Aurora Extensions <support@auroraextensions.com>
 * @license       MIT License
 */
declare(strict_types=1);

namespace AuroraExtensions\NotificationService\Api;

use Magento\Framework\Notification\MessageInterface;

interface NotificationManagementInterface
{
    /** @constant array SEVERITY */
    public const SEVERITY = [
        'critical' => MessageInterface::SEVERITY_CRITICAL,
        'major' => MessageInterface::SEVERITY_MAJOR,
        'minor' => MessageInterface::SEVERITY_MINOR,
        'notice' => MessageInterface::SEVERITY_NOTICE,
    ];

    /**
     * @param \AuroraExtensions\NotificationService\Api\Data\NotificationInterface $notification
     * @return \AuroraExtensions\NotificationService\Api\Data\NotificationInterface
     */
    public function markIsSent(Data\NotificationInterface $notification): Data\NotificationInterface;

    /**
     * @param \AuroraExtensions\NotificationService\Api\Data\NotificationInterface $notification
     * @return \AuroraExtensions\NotificationService\Api\Data\NotificationInterface
     * @throws \AuroraExtensions\NotificationService\Exception\ModuleNotEnabledException
     */
    public function send(Data\NotificationInterface $notification): Data\NotificationInterface;
}
