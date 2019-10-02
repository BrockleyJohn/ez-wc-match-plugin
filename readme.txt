=== Easify Server WooCommerce (MF) ===
Contributors: easify
Donate link: http://www.easify.co.uk/
Tags: easify, epos, epos software, stock control software, accounting software, invoicing software, small business software, ecommerce, e-commerce, woothemes, wordpress ecommerce, woocommerce, shopping cart
Requires at least: 4.0
Tested up to: 4.9
Stable tag: 4.12
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Connects Easify V4.x Small Business Software to your WooCommerce online shop, 
allowing you to synchronise stock levels and prices between your physical shop and your
online shop.

== Description ==
 
This variation on the Easify V4 woocommerce plugin built by SEwebsites only uploads price and stock changes to existing online products with matching SKUs.

This plugin connects your Easify V4.x Small Business software with your 
WooCommerce online shop.

Orders that are placed via your WooCommerce enabled website will be 
automatically sent to your Easify Server.

Products that you add to your Easify Server will be automatically uploaded to 
your WooCommerce enabled website.

As you sell products in your traditional shop, your stock levels will be 
automatically synchronised with your WooCommerce online shop.

== Installation ==

= Minimum Requirements =

* WordPress 4.0 or greater
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater
* Some payment gateways require fsockopen support (for IPN access)
* WooCommerce 2.6.2 or greater
* Easify V4.39.1 or greater
* Requires open outgoing ports in the range 1234 to 1260, some hosts may block these
* Requires open outgoing port 443, some hosts may block outgoing ports

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of the Easify WooCommerce Connector, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “Easify Server WooCommerce” and click Search Plugins. Once you’ve found our plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =

The manual installation method involves downloading our plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

Alternatively you can download the plugin and upload it from within the Wordpress control panel, Add New > Upload Plugin option.

== Frequently Asked Questions ==

= What is Easify? =

Easify is a software application for small business that runs on PCs or laptops 
with Microsoft Windows.

It provides you with stock control, invoicing, quoting, purchasing, 
EPOS software, accounting, reporting and more...

= What does the Easify V4.x WooCommerce Connector do? =

This plugin connects your Easify V4.x Small Business Software with your 
WooCommerce online shop.

Easify V4.x gives you EPOS, Stock Control, Billing, Purchasing and Accounting 
all in one easy to use package.

With this plugin and WooCommerce, Orders that are placed via your WooCommerce 
enabled website will be automatically sent to your Easify Server.

Products that you add to your Easify Server using Easify Pro V4.x will be 
automatically uploaded to your WooCommerce enabled website.

As you sell products in your traditional shop, your stock levels will be 
automatically synchronised with your WooCommerce online shop.

= Where do I get Easify V4.x Software? =
You can purchase Easify from our website - <http://www.easify.co.uk>

= Where do I get support? =

<http://www.easify.co.uk/support/contact>
support@easify.co.uk
+44 (0)1305 303040

== Screenshots ==

1. Easify, the only software you need to run your small business, including stock control, billing, purchasing, accounting, reporting etc...
2. Setup, simply enter your Easify WooCommerce Plugin subscription details and the Easify Plugin will connect to your Easify Server automatically.
3. Orders, here you can configure how WooCommerce orders are sent to your Easify Server.
4. Customers, these settings are used when customers are automatically raised in Easify.
5. Coupons, the Easify WooCommerce Plugin supports WooCommerce coupons.
6. Shipping, map various WooCommerce shipping options to your Easify Server.
7. Payment, configure how WooCommerce payments are recorded in Easify.
8. Logging, if you need it you can enable detailed logging for the Easify WooCommerce Plugin.

== Changelog ==
= 4.12 =
* Fixed issue where products that go out of stock, and then come back into stock can be hidden from WooCommerce product search. 
= 4.11 =
* Fixed basic authorisation could error out when activating plugin on certain 
systems.
= 4.10 =
* Fixed issue with multiple image uploads when images are stored in local file system on Easify Server.
= 4.9 =
* Added support for multiple product images to be uploaded from Easify to the 
product WooCommerce gallery (requires you to be running Easify V4.56 or later).
* When stock level is set to zero, WooCommerce now displays 'Out of stock'.
= 4.8 =
* Fixed error when attempting to save Coupon or Shipping SKUs in Easify Settings.
= 4.7 =
* Resolved error where outbound Product Info update notifications could get stuck in the Easify Pro eCommerce Channel queue.
= 4.6 =
* Improved error handling when destination Easify Server unreachable.
* Tested with WordPress 4.9.
= 4.5.1 =
* Added support for product tags being uploaded from Easify V4.47 or later.
* Fixed issue where duplicate product images were uploaded whenever product updated.
* Fixed issue where wp-config not found on certain web servers.
* Modified product upload code to use json instead of XML.
= 4.4 =
* Improved support for 3rd party WooCommerce shipping plugins.
* Tested with WordPress 4.8
= 4.3 =
* Resolved source control issue.
= 4.2 =
* Fixed issue with debug logging when Easify plugin logging enabled.
= 4.1 =
* Improved basic authentication handling for certain web hosts
* Plugin now supports WordPress being installed in a subdirectory
* Fixed CURL error when running PHP > 7.0.7 and CURL < 7.41.0
= 4.0 =
* Initial release for Easify V4.x.

== Upgrade Notice ==
= 4.12 =
* Fixed issue where products that go out of stock, and then come back into stock
 can be hidden from WooCommerce product search. 