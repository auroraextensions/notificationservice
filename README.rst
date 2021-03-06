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

*Bottom line*: It should be dead simple to use.

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
                        <title translate="true">v1.0.0 low-priority notification title</title>
                        <description translate="true">This is a low-priority notification about v1.0.0.</description>
                    </notification>
                    <notification index="1" severity="major">
                        <title translate="true">v1.0.0 high-priority notification title</title>
                        <description translate="true">This is a high-priority notification about v1.0.0.</description>
                        <link>https://www.example.com/</link>
                    </notification>
                </notifications>
            </release>
            <release version="1.0.1">
                <notifications>
                    <notification index="0" severity="minor">
                        <title translate="true">v1.0.1 medium-priority notification title</title>
                        <description translate="true">This is a medium-priority notification about v1.0.1.</description>
                    </notification>
                </notifications>
            </release>
        </releases>
    </config>

XML Schema
==========

To keep it simple, the XSD provides a minimal set of element and attribute types.

As with many Magento XML configurations, ``<config>`` is the root node. All other nodes are descendants of ``<config>``.

<releases>
----------

The ``<releases>`` node is the outermost node and has one (1) attribute, ``group``. The value
of the ``group`` attribute should be universally unique to prevent unwanted merging. It is an
``array``-type and should contain only ``<release>`` nodes.

==========  ================================
Element     ``<releases>``
Parent      ``<config>``
XPath       ``/config/releases``
Attributes  :group: ``string`` (Required)
Required    Yes
==========  ================================

<release>
---------

The ``<release>`` node has one (1) attribute, ``version``. The value of the ``version``
attribute should be the module version associated with the specific notification(s).
It should contain only one (1) ``<notifications>`` node.

==========  ================================
Element     ``<release>``
Parent      ``<releases>``
XPath       ``/config/releases/release``
Attributes  :version: ``string`` (Required)
Required    Yes
==========  ================================

<notifications>
---------------

The ``<notifications>`` node is an ``array``-type node and should only contain ``<notification>``
nodes. It has no associated attributes.

==========  ================================
Element     ``<notifications>``
Parent      ``<release>``
XPath       ``/config/releases/release/notifications``
Attributes  None
Required    Yes
==========  ================================

<notification>
--------------

The ``<notification>`` node describes the various components of a specific notification and has
two (2) attributes, ``index`` and ``severity``. The value of the ``index`` attribute must be an
``int``, which denotes the notification position in the resulting array of notifications. The value
of the ``severity`` attribute maps to levels defined in ``MessageInterface`` [#ref1]_, and must be one
of the following:

* ``critical``
* ``major``
* ``minor``
* ``notice``

It should contain only one (1) node per each of the following types:

* ``<title>``
* ``<description>``
* ``<link>`` (Optional)

|

==========  ================================
Element     ``<notification>``
Parent      ``<notifications>``
XPath       ``/config/releases/release/notifications/notification``
Attributes  :index: ``int`` (Required)
            :severity: ``string`` (Required)
Required    Yes
==========  ================================

<title>,<description>
---------------------

The ``<title>`` and ``<description>`` nodes comprise the corpus of the notification. The ``<title>``
node contains the text to display on the first line of the notification, and the ``<description>``
node contains the body of the notification. Both nodes provide one (1) attribute, ``translate``. The
value of the ``translate`` attribute should always be ``true``, otherwise simply omit the attribute
to prevent translation.

==========  ================================
Element     ``<title>``, ``<description>``
Parent      ``<notification>``
XPath       ``/config/releases/release/notifications/notification/*[self::title or self::description]``
Attributes  :translate: ``bool`` (Optional)
Required    Yes
==========  ================================

<link>
------

The ``<link>`` node contains a URL for the *Read Details* link. This node is optional and can be omitted.
It has no associated attributes.

==========  ================================
Element     ``<link>``
Parent      ``<notification>``
XPath       ``/config/releases/release/notifications/notification/link``
Attributes  None
Required    No
==========  ================================

Footnotes
=========

.. [#ref1] `Magento\\Framework\\Notification\\MessageInterface <https://github.com/magento/magento2/blob/2.3/lib/internal/Magento/Framework/Notification/MessageInterface.php>`_
