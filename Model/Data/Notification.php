<?php
/**
 * Notification.php
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

namespace AuroraExtensions\NotificationService\Model\Data;

use AuroraExtensions\NotificationService\{
    Api\Data\NotificationInterface,
    Model\ResourceModel\Notification as NotificationResourceModel
};
use Magento\Framework\Model\AbstractModel;

class Notification extends AbstractModel implements NotificationInterface
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(NotificationResourceModel::class);
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->getData('created_at');
    }

    /**
     * @param string $createdAt
     * @return NotificationInterface
     */
    public function setCreatedAt($createdAt): NotificationInterface
    {
        $this->setData('created_at', $createdAt);
        return $this;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return (int) $this->getData('index');
    }

    /**
     * @param int $index
     * @return NotificationInterface
     */
    public function setIndex(int $index): NotificationInterface
    {
        $this->setData('index', $index);
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->getData('version');
    }

    /**
     * @param string $version
     * @return NotificationInterface
     */
    public function setVersion(string $version): NotificationInterface
    {
        $this->setData('version', $version);
        return $this;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->getData('group');
    }

    /**
     * @param string $group
     * @return NotificationInterface
     */
    public function setGroup(string $group): NotificationInterface
    {
        $this->setData('group', $group);
        return $this;
    }

    /**
     * @return string
     */
    public function getXpath(): string
    {
        return $this->getData('xpath');
    }

    /**
     * @param string $xpath
     * @return NotificationInterface
     */
    public function setXpath(string $xpath): NotificationInterface
    {
        $this->setData('xpath', $xpath);
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsSent(): bool
    {
        return (bool) $this->getData('is_sent');
    }

    /**
     * @param bool $isSent
     * @return NotificationInterface
     */
    public function setIsSent(bool $isSent): NotificationInterface
    {
        $this->setData('is_sent', $isSent);
        return $this;
    }
}
