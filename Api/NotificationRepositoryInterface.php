<?php
/**
 * NotificationRepositoryInterface.php
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

interface NotificationRepositoryInterface
{
    /**
     * @param string $xpath
     * @return \AuroraExtensions\NotificationService\Api\Data\NotificationInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(string $xpath): Data\NotificationInterface;

    /**
     * @param int $id
     * @return \AuroraExtensions\NotificationService\Api\Data\NotificationInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): Data\NotificationInterface;

    /**
     * @param \AuroraExtensions\NotificationService\Api\Data\NotificationInterface $notification
     * @return int
     */
    public function save(Data\NotificationInterface $notification): int;

    /**
     * @param \AuroraExtensions\NotificationService\Api\Data\NotificationInterface $notification
     * @return bool
     */
    public function delete(Data\NotificationInterface $notification): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;
}
