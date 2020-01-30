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
 * https://docs.auroraextensions.com/magento/extensions/2.x/notificationoutbox/LICENSE.txt
 *
 * @package       AuroraExtensions_NotificationOutbox
 * @copyright     Copyright (C) 2020 Aurora Extensions <support@auroraextensions.com>
 * @license       MIT License
 */
declare(strict_types=1);

namespace AuroraExtensions\NotificationOutbox\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Notification extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'notificationoutbox_entry',
            'entry_id'
        );
    }
}
