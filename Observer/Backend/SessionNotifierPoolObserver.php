<?php
/**
 * SessionNotifierPoolObserver.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/notificationnotifier/LICENSE.txt
 *
 * @package       AuroraExtensions_NotificationOutbox
 * @copyright     Copyright (C) 2020 Aurora Extensions <support@auroraextensions.com>
 * @license       MIT License
 */
declare(strict_types=1);

namespace AuroraExtensions\NotificationOutbox\Observer\Backend;

use AuroraExtensions\NotificationOutbox\Api\NotificationManagementInterface;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\{
    Event\Observer,
    Event\ObserverInterface
};

class SessionNotifierPoolObserver implements ObserverInterface
{
    /** @property NotificationManagementInterface $notifier */
    private $notifier;

    /** @property Session $session */
    private $session;

    /**
     * @param NotificationManagementInterface $notifier
     * @param Session $session
     * @return void
     */
    public function __construct(
        NotificationManagementInterface $notifier,
        Session $session
    ) {
        $this->notifier = $notifier;
        $this->session = $session;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if (!$this->session->isLoggedIn()) {
            return;
        }

        if ($this->notifier->hasUnsent()) {
            $this->notifier->processUnsent();
        }
    }
}