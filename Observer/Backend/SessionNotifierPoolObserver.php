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
 * @package       AuroraExtensions_NotificationService
 * @copyright     Copyright (C) 2020 Aurora Extensions <support@auroraextensions.com>
 * @license       MIT License
 */
declare(strict_types=1);

namespace AuroraExtensions\NotificationService\Observer\Backend;

use AuroraExtensions\NotificationService\{
    Api\NotificationManagementInterface,
    Component\Config\ModuleConfigTrait,
    Csi\Config\ModuleConfigInterface
};
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\{
    Event\Observer,
    Event\ObserverInterface
};

class SessionNotifierPoolObserver implements ObserverInterface
{
    /**
     * @property ModuleConfigInterface $moduleConfig
     * @method bool isModuleEnabled()
     */
    use ModuleConfigTrait;

    /** @property NotificationManagementInterface $notifier */
    private $notifier;

    /** @property Session $session */
    private $session;

    /**
     * @param ModuleConfigInterface $moduleConfig
     * @param NotificationManagementInterface $notifier
     * @param Session $session
     * @return void
     */
    public function __construct(
        ModuleConfigInterface $moduleConfig,
        NotificationManagementInterface $notifier,
        Session $session
    ) {
        $this->moduleConfig = $moduleConfig;
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

        if ($this->isModuleEnabled()) {
            $this->notifier->processUnsent();
        }
    }
}
