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
 * https://docs.auroraextensions.com/magento/extensions/2.x/notificationoutbox/LICENSE.txt
 *
 * @package       AuroraExtensions_NotificationOutbox
 * @copyright     Copyright (C) 2020 Aurora Extensions <support@auroraextensions.com>
 * @license       MIT License
 */
declare(strict_types=1);

namespace AuroraExtensions\NotificationOutbox\Api;

interface NotificationRepositoryInterface
{
    /**
     * @param string $xpath
     * @return \AuroraExtensions\NotificationOutbox\Api\Data\NotificationInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(string $xpath): Data\NotificationInterface;

    /**
     * @param int $id
     * @return \AuroraExtensions\NotificationOutbox\Api\Data\NotificationInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): Data\NotificationInterface;

    /**
     * @param \AuroraExtensions\NotificationOutbox\Api\Data\NotificationInterface $entry
     * @return int
     */
    public function save(Data\NotificationInterface $entry): int;

    /**
     * @param \AuroraExtensions\NotificationOutbox\Api\Data\NotificationInterface $entry
     * @return bool
     */
    public function delete(Data\NotificationInterface $entry): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;
}
