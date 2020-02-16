<?php
/**
 * NotificationManagement.php
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

namespace AuroraExtensions\NotificationService\Model\Management;

use Exception;
use AuroraExtensions\NotificationService\{
    Api\Data\NotificationInterface,
    Api\Data\NotificationInterfaceFactory,
    Api\NotificationManagementInterface,
    Api\NotificationRepositoryInterface,
    Component\Config\ModuleConfigTrait,
    Csi\Config\ModuleConfigInterface,
    Exception\ExceptionFactory,
    Exception\ModuleNotEnabledException
};
use Magento\Framework\{
    Config\ReaderInterface,
    Exception\CouldNotSaveException,
    Exception\LocalizedException,
    Exception\NoSuchEntityException,
    Notification\MessageInterface,
    Notification\NotifierInterface
};
use Psr\Log\LoggerInterface;

class NotificationManagement implements NotificationManagementInterface
{
    /**
     * @property ModuleConfigInterface $moduleConfig
     * @method bool isModuleEnabled()
     */
    use ModuleConfigTrait;

    /** @property ExceptionFactory $exceptionFactory */
    protected $exceptionFactory;

    /** @property LoggerInterface $logger */
    protected $logger;

    /** @property NotificationInterfaceFactory $notificationFactory */
    protected $notificationFactory;

    /** @property NotificationRepositoryInterface $notificationRepository */
    protected $notificationRepository;

    /** @property NotifierInterface $notifierPool */
    protected $notifierPool;

    /** @property ReaderInterface $xmlReader */
    protected $xmlReader;

    /**
     * @param ExceptionFactory $exceptionFactory
     * @param LoggerInterface $logger
     * @param ModuleConfigInterface $moduleConfig
     * @param NotificationInterfaceFactory $notificationFactory
     * @param NotificationRepositoryInterface $notificationRepository
     * @param NotifierInterface $notifierPool
     * @param ReaderInterface $xmlReader
     * @return void
     */
    public function __construct(
        ExceptionFactory $exceptionFactory,
        LoggerInterface $logger,
        ModuleConfigInterface $moduleConfig,
        NotificationInterfaceFactory $notificationFactory,
        NotificationRepositoryInterface $notificationRepository,
        NotifierInterface $notifierPool,
        ReaderInterface $xmlReader
    ) {
        $this->exceptionFactory = $exceptionFactory;
        $this->logger = $logger;
        $this->moduleConfig = $moduleConfig;
        $this->notificationFactory = $notificationFactory;
        $this->notificationRepository = $notificationRepository;
        $this->notifierPool = $notifierPool;
        $this->xmlReader = $xmlReader;
    }

    /**
     * {@inheritdoc}
     */
    public function markIsSent(NotificationInterface $notification): NotificationInterface
    {
        try {
            $this->notificationRepository->save(
                $notification->setIsSent(true)
            );
        } catch (CouldNotSaveException | LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }

        return $notification;
    }

    /**
     * @return void
     */
    public function processUnsent(): void
    {
        /** @var array $data */
        $data = $this->xmlReader->read();

        /** @var string $group */
        /** @var array $versions */
        foreach ($data as $group => $versions) {
            $this->processGroup($group, $versions);
        }
    }

    /**
     * @param string $group
     * @param array $versions
     * @return void
     */
    private function processGroup(
        string $group,
        array $versions = []
    ): void
    {
        /** @var string $version */
        /** @var array $messages */
        foreach ($versions as $version => $messages) {
            $this->processVersion(
                $group,
                $version,
                $messages
            );
        }
    }

    /**
     * @param string $group
     * @param string $version
     * @param array $messages
     * @return void
     */
    private function processVersion(
        string $group,
        string $version,
        array $messages = []
    ): void
    {
        /** @var array $message */
        foreach ($messages as $message) {
            /** @var string $severity */
            $severity = $message['severity'] ?? 'notice';

            /** @var string $index */
            $index = $message['index'] ?? '0';

            /** @var bool $isIgnored */
            $isIgnored = $message['ignore'];

            /** @var string $xpath */
            $xpath = implode('/', [
                $group,
                $version,
                $index
            ]);

            try {
                /** @var NotificationInterface $notification */
                $notification = $this->notificationRepository->get($xpath);

                if (!$isIgnored && !$notification->getIsSent()) {
                    $this->send($notification);
                }
            } catch (NoSuchEntityException $e) {
                /** @var string $title */
                $title = $message['title'];

                /** @var string $description */
                $description = $message['description'] ?? '';

                /** @var string $link */
                $link = $message['link'] ?? '';

                /** @var NotificationInterface $notification */
                $notification = $this->notificationFactory->create();
                $notification->addData([
                    'group' => $group,
                    'version' => $version,
                    'index' => $index,
                    'xpath' => $xpath,
                    'severity' => $severity,
                    'title' => $title,
                    'description' => $description,
                    'link' => $link,
                    'is_sent' => !$isIgnored
                ]);
                $this->notificationRepository->save($notification);

                if (!$isIgnored) {
                    $this->send($notification);
                }
            } catch (LocalizedException | Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function send(NotificationInterface $notification): NotificationInterface
    {
        if (!$this->isModuleEnabled()) {
            /** @var ModuleNotEnabledException $exception */
            $exception = $this->exceptionFactory->create(
                ModuleNotEnabledException::class
            );

            throw $exception;
        }

        /** @var string $group */
        $group = $notification->getGroup();

        /** @var string $version */
        $version = $notification->getVersion();

        /** @var int $index */
        $index = $notification->getIndex() ?? 0;

        /** @var string $xpath */
        $xpath = implode('/', [
            $group,
            $version,
            $index,
        ]);

        /** @var string $severity */
        $severity = $notification->getSeverity();

        /** @var string $title */
        $title = $notification->getTitle();

        /** @var string $description */
        $description = $notification->getDescription() ?? '';

        /** @var string $link */
        $link = $notification->getLink() ?? '';

        try {
            $this->notifierPool->add(
                static::SEVERITY[$severity],
                $title,
                $description,
                $link
            );

            $notification->addData([
                'index' => $index,
                'xpath' => $xpath,
                'is_sent' => true,
            ]);

            $this->notificationRepository->save($notification);
        } catch (LocalizedException | Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $notification;
    }
}
