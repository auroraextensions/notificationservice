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
 * https://docs.auroraextensions.com/magento/extensions/2.x/notificationoutbox/LICENSE.txt
 *
 * @package       AuroraExtensions_NotificationOutbox
 * @copyright     Copyright (C) 2020 Aurora Extensions <support@auroraextensions.com>
 * @license       MIT License
 */
declare(strict_types=1);

namespace AuroraExtensions\NotificationOutbox\Model\Management;

use Exception;
use AuroraExtensions\NotificationOutbox\{
    Api\Data\NotificationInterface,
    Api\Data\NotificationInterfaceFactory,
    Api\NotificationManagementInterface,
    Api\NotificationRepositoryInterface,
    Model\Config\FileReader
};
use Magento\Framework\{
    Exception\CouldNotSaveException,
    Exception\LocalizedException,
    Exception\NoSuchEntityException,
    Notification\MessageInterface,
    Notification\NotifierInterface
};
use Psr\Log\LoggerInterface;

class NotificationManagement implements NotificationManagementInterface
{
    /** @property NotificationInterfaceFactory $entryFactory */
    protected $entryFactory;

    /** @property NotificationRepositoryInterface $entryRepository */
    protected $entryRepository;

    /** @property FileReader $fileReader */
    protected $fileReader;

    /** @property LoggerInterface $logger */
    protected $logger;

    /** @property NotifierInterface $notifierPool */
    protected $notifierPool;

    /**
     * @param NotificationInterfaceFactory $entryFactory
     * @param NotificationRepositoryInterface $entryRepository
     * @param FileReader $fileReader
     * @param LoggerInterface $logger
     * @param NotifierInterface $notifierPool
     * @return void
     */
    public function __construct(
        NotificationInterfaceFactory $entryFactory,
        NotificationRepositoryInterface $entryRepository,
        FileReader $fileReader,
        LoggerInterface $logger,
        NotifierInterface $notifierPool
    ) {
        $this->entryFactory = $entryFactory;
        $this->entryRepository = $entryRepository;
        $this->fileReader = $fileReader;
        $this->logger = $logger;
        $this->notifierPool = $notifierPool;
    }

    /**
     * {@inheritdoc}
     */
    public function hasUnsent(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function markAsSent(NotificationInterface $entry): void
    {
        try {
            $this->entryRepository->save(
                $entry->setIsSent(true)
            );
        } catch (CouldNotSaveException | LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function processUnsent(): void
    {
        /** @var array $data */
        $data = $this->fileReader->read();

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
            $severity = $message['severity'] ?? 'major';

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
                /** @var NotificationInterface $entry */
                $entry = $this->entryRepository->get($xpath);

                if (!$isIgnored && !$entry->getIsSent()) {
                    $this->send($message);
                    $this->markAsSent($entry);
                }
            } catch (NoSuchEntityException $e) {
                /** @var NotificationInterface $entry */
                $entry = $this->entryFactory->create();

                $entry->addData([
                    'group' => $group,
                    'version' => $version,
                    'index' => $index,
                    'xpath' => $xpath,
                    'is_sent' => !$isIgnored
                ]);
                $this->entryRepository->save($entry);

                if (!$isIgnored) {
                    $this->send($message);
                    $this->markAsSent($entry);
                }
            } catch (LocalizedException | Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }
    }

    /**
     * @param array $message
     * @return void
     */
    private function send(array $message = []): void
    {
        try {
            /** @var string $severity */
            $severity = $message['severity'] ?? 'notice';

            $this->notifierPool->add(
                static::SEVERITY[$severity],
                $message['title'],
                $message['description']
            );
        } catch (LocalizedException | Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
