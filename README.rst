.. contents:: :local:

Description
===========

Magento provides a backend notification system that lacks powerful features, like auto-publish
and discovery. Consequently, many extension vendors don't effectively utilize it for important
announcements, like API-breaking releases, EOL notices, or deprecation of specific extension
features, which can lead to transparency issues.

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

To keep it simple, the XSD only provides for a few element and attribute types.

As with most other Magento XML configurations, the ``<config>`` tag serves as the root node.
All subsequent nodes are nested under the ``<config>`` node.

<releases>
----------

The ``<releases>`` node is the outermost node and has one (1) attribute, ``group``. The value
of the ``group`` attribute should be universally unique to prevent unwanted merging. It is an
``array``-type node.

==========  ================================
Element     ``<releases>``
XPath       ``/config/releases``
Attributes  :group: ``string`` (Required)
Required    Yes
==========  ================================

<release>
---------

The ``<release>`` node has one (1) attribute, ``version``. The value of the ``version``
attribute should be the module version associated with the specific notification(s).

==========  ================================
Element     ``<release>``
XPath       ``/config/releases/release``
Parent      ``<releases>``
Attributes  :version: ``string`` (Required)
Required    Yes
==========  ================================

<notifications>
---------------

The ``<notifications>`` node contains only ``<notification>`` nodes and has no associated
attributes. It is an ``array``-type node.

==========  ================================
Element     ``<notifications>``
XPath       ``/config/releases/release/notifications``
Parent      ``<release>``
Attributes  None
Required    Yes
==========  ================================

<notification>
--------------

The ``<notification>`` node describes the various components of a specific notification and has
two (2) attributes, ``index`` and ``severity``. The value of the ``index`` attribute must be an
``int`` and denotes the notifications position in the resulting array of notifications. The value
of the ``severity`` attribute maps to levels defined in ``Magento\Framework\Notification\MessageInterface``,
and must be one of the following:

* ``critical``
* ``major``
* ``minor``
* ``notice``

==========  ================================
Element     ``<notification>``
XPath       ``/config/releases/release/notifications/notification``
Parent      ``<notifications>``
Attributes  :index: ``int`` (Required)
            :severity: ``string`` (Required)
Required    Yes
==========  ================================

<title>,<description>
---------------------

The ``<title>`` and ``<description>`` nodes comprise the corpus of the notification. The ``<title>``
node contains the text to display on the first line of the notification, and the ``<description>``
node contains the body of the notification. Both nodes accept one (1) attribute, ``translate``. The
value of the ``translate`` attribute should always be ``true``, otherwise simply omit the attribute
for the equivalent of ``false``.

==========  ================================
Element     ``<title>``, ``<description>``
XPath       ``/config/releases/release/notifications/notification/*[self::title or self::description]``
Parent      ``<notification>``
Attributes  :translate: ``bool`` (Optional)
Required    Yes
==========  ================================

<link>
------

The ``<link>`` node contains a URL for the *Read Details* link. This node is optional and can be omitted.

==========  ================================
Element     ``<link>``
XPath       ``/config/releases/release/notifications/notification/link``
Parent      ``<notification>``
Attributes  None
Required    No
==========  ================================
