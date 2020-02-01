.. contents:: :local:

Description
===========

Magento provides a backend notification system that lacks powerful features, like auto-publish
and discovery. Consequently, many extension vendors don't effectively utilize it for important
announcements, like API-breaking releases, EOL notices, or deprecation of specific extension
features, which can lead to issues for agencies and merchants alike.

To address this issue, wouldn't it be nice if you could just add, say, an XML file to your module,
which contains the notifications you want to publish, and the rest is handled by the notification
system? It would remove the need for notification-specific models and data patches, and would not
require setup upgrade to run in order for new notifications to publish.

*Bottom line*: It needs to be dead simple to use.

Installation
============

.. code-block:: sh

    composer require auroraextensions/notificationservice

Configuration
=============

Configuration is as simple as adding a ``notifications.xml`` file to the module ``etc`` directory.

Examples
========

Below is an example ``notifications.xml``. For information on schema, see `XML Schema`_.

.. code-block:: XML

    <?xml version="1.0"?>
    <!--
    /**
     * notifications.xml
     */
    -->
    <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:noNamespaceSchemaLocation="urn:magento:module:AuroraExtensions_NotificationService/etc/notifications.xsd">
        <releases group="notificationservice">
            <release version="1.0.0">
                <notifications>
                    <notification index="0" severity="notice">
                        <title translate="true">Notification title</title>
                        <description translate="true">This is the notification description.</description>
                        <link>https://docs.auroraextensions.com/magento/extensions/2.x/notificationservice/latest/</link>
                    </notification>
                </notifications>
            </release>
        </releases>
    </config>

XML Schema
==========

To keep it simple, there are only a few element and attribute types provided by the XSD.
Element and attribute types are marked accordingly, along with XSD requirements.

.. _notificationservice_xml_schema_element_releases:

==========  ================================
Element     ``<releases>``
Attributes  :group: ``string`` (Required)
Required    Yes
==========  ================================

|

.. _notificationservice_xml_schema_element_release:

==========  ================================
Element     ``<release>``
Parent      ``<releases>``
Attributes  :version: ``string`` (Required)
Required    Yes
==========  ================================

|

.. _notificationservice_xml_schema_element_notifications:

==========  ================================
Element     ``<notifications>``
Parent      ``<release>``
Attributes  None
Required    Yes
==========  ================================

|

.. _notificationservice_xml_schema_element_notification:

==========  ================================
Element     ``<notification>``
Parent      ``<notifications>``
Attributes  :index: ``int`` (Required)
            :severity: ``string`` (Required)
Required    Yes
==========  ================================

|

.. _notificationservice_xml_schema_elements_title_description:

==========  ================================
Element     ``<title>``, ``<description>``
Parent      ``<notification>``
Attributes  :translate: ``bool`` (Optional)
Required    Yes
==========  ================================

|

.. _notificationservice_xml_schema_element_link:

==========  ================================
Element     ``<link>``
Parent      ``<notification>``
Attributes  None
Required    No
==========  ================================
