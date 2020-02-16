<?php
/**
 * ModuleConfig.php
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

namespace AuroraExtensions\NotificationService\Model\Config;

use AuroraExtensions\NotificationService\Csi\Config\ModuleConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\{
    Model\ScopeInterface as StoreScopeInterface,
    Model\Store
};

class ModuleConfig implements ModuleConfigInterface
{
    /** @constant string XML_PATH_MODULE_GENERAL_MODULE_ENABLE */
    public const XML_PATH_MODULE_GENERAL_MODULE_ENABLE = 'notificationservice/general/enable';

    /** @property ScopeConfigInterface $scopeConfig */
    protected $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @return void
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param int $store
     * @param string $scope
     * @return bool
     */
    public function isModuleEnabled(
        int $store = Store::DEFAULT_STORE_ID,
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT
    ): bool
    {
        return $this->scopeConfig->isSetFlag(
            static::XML_PATH_MODULE_GENERAL_MODULE_ENABLE,
            $scope,
            $store
        );
    }
}
