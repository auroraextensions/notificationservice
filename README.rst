.. contents:: :local:

Notification Service
====================

Publish backend notifications via XML.

Description
-----------

The process to publish notifications in Magento is not exactly ideal. Consequently, most
extension vendors don't bother to use it for important events like API breaking changes,
EOL announcements, or deprecation of certain extension features.

Wouldn't it be nice if you could just add, say, an XML file to your module that contains
the notifications you want to publish, and that's all? We believe improving transparency
between extension vendors and merchants is crucial to the longevity of both parties, as
well as Magento as an ecommerce platform, and this serves as another step toward better,
more transparent communications between vendors and merchants.

Installation
------------

.. code-block:: sh

    composer require auroraextensions/notificationservice

Configuration
-------------

Configuration is as simple as adding a ``notifications.xml`` file to the module ``etc`` directory.

XML Schema
----------

To keep it simple, there are only a few element and attribute types provided by the XSD.
Element and attribute types are marked accordingly, along with XSD requirements.

==========  ================================
Element     ``<releases>``
Attributes  :group: ``string`` (Required)
Required    Yes
==========  ================================

|

==========  ================================
Element     ``<release>``
Parent      ``<releases>``
Attributes  :version: ``string`` (Required)
Required    Yes
==========  ================================

|

==========  ================================
Element     ``<notifications>``
Parent      ``<release>``
Attributes  None
Required    Yes
==========  ================================

|

==========  ================================
Element     ``<notification>``
Parent      ``<notifications>``
Attributes  :index: ``int`` (Required)
            :severity: ``string`` (Required)
Required    Yes
==========  ================================

|

==========  ================================
Element     ``<title>``, ``<description>``
Parent      ``<notification>``
Attributes  :translate: ``bool`` (Optional)
Required    Yes
==========  ================================

==========  ================================
Element     ``<link>``
Parent      ``<notification>``
Attributes  None
Required    Yes
==========  ================================

Examples
--------

Below is an example ``notifications.xml``. For information on schema, visit the
`XML Schema`_ section.

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
