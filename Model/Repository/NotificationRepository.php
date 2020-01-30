<?php
/**
 * NotificationRepository.php
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

namespace AuroraExtensions\NotificationOutbox\Model\Repository;

use AuroraExtensions\NotificationOutbox\{
    Api\AbstractCollectionInterface,
    Api\NotificationRepositoryInterface,
    Api\Data\NotificationInterface,
    Api\Data\NotificationInterfaceFactory,
    Exception\ExceptionFactory,
    Model\ResourceModel\Notification as NotificationResource
};
use Magento\Framework\{
    Api\SearchResultsInterface,
    Api\SearchResultsInterfaceFactory,
    Exception\NoSuchEntityException
};

class NotificationRepository implements NotificationRepositoryInterface
{
    /** @property ExceptionFactory $exceptionFactory */
    protected $exceptionFactory;

    /** @property NotificationInterfaceFactory $notificationFactory */
    protected $notificationFactory;

    /** @property NotificationResource $notificationResource */
    protected $notificationResource;

    /**
     * @param ExceptionFactory $exceptionFactory
     * @param NotificationInterfaceFactory $notificationFactory
     * @param NotificationResource $notificationResource
     * @return void
     */
    public function __construct(
        ExceptionFactory $exceptionFactory,
        NotificationInterfaceFactory $notificationFactory,
        NotificationResource $notificationResource
    ) {
        $this->exceptionFactory = $exceptionFactory;
        $this->notificationFactory = $notificationFactory;
        $this->notificationResource = $notificationResource;
    }

    /**
     * @param string $xpath
     * @return NotificationInterface
     * @throws NoSuchEntityException
     */
    public function get(string $xpath): NotificationInterface
    {
        /** @var NotificationInterface $notification */
        $notification = $this->notificationFactory->create();
        $this->notificationResource->load(
            $notification,
            $xpath,
            'xpath'
        );

        if (!$notification->getId()) {
            /** @var NoSuchEntityException $exception */
            $exception = $this->exceptionFactory->create(
                NoSuchEntityException::class,
                __('Unable to locate notification information.')
            );

            throw $exception;
        }

        return $notification;
    }

    /**
     * @param int $id
     * @return NotificationInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): NotificationInterface
    {
        /** @var NotificationInterface $notification */
        $notification = $this->notificationFactory->create();
        $this->notificationResource->load($notification, $id);

        if (!$notification->getId()) {
            /** @var NoSuchEntityException $exception */
            $exception = $this->exceptionFactory->create(
                NoSuchEntityException::class,
                __('Unable to locate notification information.')
            );

            throw $exception;
        }

        return $notification;
    }

    /**
     * @param NotificationInterface $notification
     * @return int
     */
    public function save(NotificationInterface $notification): int
    {
        $this->notificationResource->save($notification);
        return (int) $notification->getId();
    }

    /**
     * @param NotificationInterface $notification
     * @return bool
     */
    public function delete(NotificationInterface $notification): bool
    {
        return $this->deleteById((int) $notification->getId());
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        /** @var NotificationInterface $notification */
        $notification = $this->notificationFactory->create();
        $notification->setId($id);

        return (bool) $this->notificationResource->delete($notification);
    }
}
