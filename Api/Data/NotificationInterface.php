<?php
/**
 * NotificationInterface.php
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

interface NotificationInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \AuroraExtensions\NotificationService\Api\Data\NotificationInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param \DateTime|string $createdAt
     * @return \AuroraExtensions\NotificationService\Api\Data\NotificationInterface
     */
    public function setCreatedAt($createdAt): NotificationInterface;

    /**
     * @return int
     */
    public function getIndex(): int;

    /**
     * @param int $index
     * @return \AuroraExtensions\NotificationService\Api\Data\NotificationInterface
     */
    public function setIndex(int $index): NotificationInterface;

    /**
     * @return string
     */
    public function getVersion(): string;

    /**
     * @param string $version
     * @return \AuroraExtensions\NotificationService\Api\Data\NotificationInterface
     */
    public function setVersion(string $version): NotificationInterface;

    /**
     * @return string
     */
    public function getGroup(): string;

    /**
     * @param string $group
     * @return \AuroraExtensions\NotificationService\Api\Data\NotificationInterface
     */
    public function setGroup(string $group): NotificationInterface;

    /**
     * @return string
     */
    public function getXpath(): string;

    /**
     * @param string $xpath
     * @return \AuroraExtensions\NotificationService\Api\Data\NotificationInterface
     */
    public function setXpath(string $xpath): NotificationInterface;

    /**
     * @return bool
     */
    public function getIsSent(): bool;

    /**
     * @param bool $isSent
     * @return \AuroraExtensions\NotificationService\Api\Data\NotificationInterface
     */
    public function setIsSent(bool $isSent): NotificationInterface;
}
