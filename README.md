# Notification Service

Publish backend notifications via XML.

## Table of Contents

+ [Description](#description)
+ [Installation](#installation)
+ [Configuration](#configuration)
+ [Examples](#examples)

## Description

The process to publish notifications in Magento is not exactly ideal. Consequently, most
extension vendors don't bother to use it for important events like API breaking changes,
EOL announcements, or deprecation of certain extension features.

Wouldn't it be nice if you could just add, say, an XML file to your module that contains
the notifications you want to publish, and that's all? We believe improving transparency
between extension vendors and merchants is crucial to the longevity of both parties, as
well as Magento as an ecommerce platform, and this serves as another step toward better,
more transparent communications between vendors and merchants.

## Installation

```
composer require auroraextensions/notificationservice
```

## Configuration

Using Notification Service is as simple as adding a `notifications.xml` file to the `etc`
directory of your module. Below are configuration options and examples.

| Options     | Description                                                                                   | Type   | Required |
|-------------|-----------------------------------------------------------------------------------------------|--------|----------|
| title       | The title of the notification. This value is displayed as the first line of the notification. | string | Yes      |
| description | The description of the notification. This value is displayed under the title.                 | string | Yes      |
| link        | The notification link. This value is the href of the Read Details link.                       | string | No       |

## Examples

This module contains its own `notifications.xml` file, which hopefully serves as an effective example configuration.

```xml
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
                    <title translate="true">Initial release of Notification Service by Aurora Extensions.</title>
                    <description translate="true">This is the initial release of Notification Service by Aurora Extensions.</description>
                    <link>https://docs.auroraextensions.com/magento/extensions/2.x/notificationservice/latest/</link>
                </notification>
            </notifications>
        </release>
    </releases>
</config>

```
