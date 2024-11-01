=== WooCommerce Cryptoo.me Instant Bitcoin payment gateway ===
Contributors: trof
Donate link: https://www.donationalerts.com/r/svinuga
Tags: woocommerce, bitcoin, satoshi, payment, gateway, cryptoo
Requires at least: 3.3
Tested up to: 5.5
Stable tag: 1.1.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

WooCommerce instant Bitcoin / satoshi checkouts via Bitcoin Micropayment Platform Cryptoo.me.

== Description ==

Provides WooCommerce stores the instant pay with Bitcoin satoshi via <a href='https://cryptoo.me'>Cryptoo.me Bitcoin Micropayment Platform</a>.
[youtube https://www.youtube.com/watch?v=8lvCfM7Wor0]
<a href='https://cryptoo.me/api-doc/'>Cryptoo.me API</a>
Please note: if you plan to sell in fiat currency (like USD) and Bitcoin, you also will need some kind of multi-currency plugin like "WooCommerce Currency Switcher"

**Demos**<br>
* <a href="http://gra4.com/shop" target="_blank">Satoshi Webstore</a> - very basic Webstore with two payment methods, just proof of concept. It is not pretty (runs on Twenty Eleven theme) - yours will be much better :) <br>


== Installation ==

= Minimum Requirements = 

* WordPress 3.3 or greater
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater
* WooCommerce 1.6 or greater

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t even need to leave your web browser. To do an automatic install of the plugin, log in to your WordPress admin panel, navigate to the Plugins menu and click Add New.

= Manual installation =

The manual installation method involves downloading our payment plugin and uploading it to your webserver via your favourite FTP application.

1. Download the plugin file to your computer and unzip it
2. Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation’s wp-content/plugins/ directory.
3. Activate the plugin from the Plugins menu within the WordPress admin.

= Configuration =
Configuration of this plugin is done via WooCommerce Payments configuration tab.
You will need <a target="_blank" href="https://cryptoo.me">Cryptoo.me</a> Application key. It is free indeed. 
Register, create new Application at <a target="_blank" href="https://cryptoo.me/applications/">Application Manager</a>, and use the Application API key in the form.
Set Application Name to your store name, and Application URL to the store URL to receive extra traffic from the Applications List

= Upgrading =

Automatic updates should work a charm; as always though, ensure you backup your site just in case.

If on the off chance you do encounter issues with the shop/category pages after an update you simply need to flush the permalinks by going to WordPress > Settings > Permalinks and hitting 'save'. That should return things to normal.

== Frequently Asked Questions ==

= I already sell in USD (RUR,EUR..), why do I switch to Bitcoin ? =

Do not switch - add. If you considering other currencies - search for WooCommerce multi-currency plugins.
See  example: <a href="http://gra4.com/shop" target="_blank">Webstore accepting BTC and USD</a>.
If you just opening online store, the Instant Bitcoin is good place to start - there is no approval process.
In any case, this payment method is really handy for downloadable products. 

There are plenty of excellent  multi-currency plugins out there, but we recommend "WooCommerce Currency Switcher".

Some advices:

1. If you use several currencies, set Bitcoin as second one. Some payment gateways ( not us! :) ) will disable themselves if primary store currency is not the one they support. 
1. Make sure you set 8 digits after decimal point for Bitcoin to operate with satochi.
1. Use fixed prices, because automatic conversion often turns small USD amounts into 0 Bitcoins. 

= What do I do with acquired satoshi  ? =

There are plenty of services willing to buy crypto-currency for fiat - google it.
You also may sell satoshi for fiat by plugin <a href="https://wordpress.org/plugins/exchange-paypal-to-satoshi/" >Exchange PayPal to Satoshi plugin.</a>

= What do I need to configure the plugin ? =

You will need only Cryptoo.me API key. It is free. 

= How do I obtain Cryptoo.me API key ? =

1. Register with Cryptoo.me .
1. Navigate to <a href='https://cryptoo.me/applications/'>Application Manager</a> .
1. Register new Application. 
We recommend to to use attractive Application Name and URL of your store to get extra customers for <a href='https://cryptoo.me/applications/'>Application List</a> .
1. Grab the API key, use it in the Payment Gateway configuration page (you can find it under WooComerce -> Configuration -> Payments)
1. You all set !

= What about the fees? =
The purchase will cost your customer 1% extra. See Cryptoo.me <a href='https://cryptoo.me/fees/'>Fees and Limits</a>. 

== Translations ==
* English - included
* Russian - included

== Changelog ==
= V1.1.0 - 06.09.2019 =
* Tested with new WooCommerce. 

= V1.1.0 - 06.09.2019 =
* Configuration simplified. 

= V1.0.2 - 12.12.2018 =
* Minor fixes, code clean-up

= V1.0.1 - 05.12.2018 =
* Initial release
