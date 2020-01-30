<?php
/**
 * SchemaLocator.php
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

namespace AuroraExtensions\NotificationOutbox\Model\Config;

use Magento\Framework\{
    Config\SchemaLocatorInterface,
    Module\Dir as ModuleDir
};

class SchemaLocator implements SchemaLocatorInterface
{
    /** @constant string MODULE_NAME */
    public const MODULE_NAME = 'AuroraExtensions_NotificationOutbox';

    /** @constant string XSD_FILE */
    public const XSD_FILE = 'notifications.xsd';

    /** @property ModuleReader $moduleReader */
    protected $moduleReader;

    /** @property string $schema */
    protected $schema;

    /**
     * @param ModuleReader $moduleReader
     * @return void
     */
    public function __construct(
        ModuleReader $moduleReader
    ) {
        $this->moduleReader = $moduleReader;
        $this->schema = $this->getXsdFile();
    }

    /**
     * @return string
     */
    protected function getXsdFile(): string
    {
        /** @var string $dirPath */
        $dirPath = $this->moduleReader
            ->getModuleDir(
                ModuleDir::MODULE_ETC_DIR,
                static::MODULE_NAME
            );

        return $dirPath . '/' . static::XSD_FILE;
    }

    /**
     * {@inheritdoc}
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * {@inheritdoc}
     */
    public function getPerFileSchema()
    {
        return $this->schema;
    }
}
