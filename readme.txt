=== Content by Country ===
Contributors: Simon99
Donate link: http://www.simonemery.co.uk/wp-plugins/content-by-country/
Tags: targeted content, geolocation
Requires at least: 2.0
Tested up to: 2.51
Stable tag: 1.00

Description: Adds the ability to target post content by a visitor's country location.

== Description ==

Using [cbc][/cbc] tags, you can set content to be included or excluded when a visitor views a post, depending on their
IP's country of origin.

Here is a typical example of the [cbc][/cbc] tags being used:

[cbc:+usa:+gb]My example content here.[/cbc]

Each country code used is in the tags is separated by a : and followed by either a + (to include a country) or - (to exclude a country) from
viewing the content. The example above would only display the content between the tags to visitors with an IP address from the
USA or the UK.

== Installation ==

1. Upload the `content-by-country` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. You can now start using [cbc][/cbc] tags to target content by a visitor's country of origin in posts

== Frequently Asked Questions ==

= What is the tag format used to target content by country? =

[cbc:+usa]My example content here.[/cbc]

Each country code used in the tags is separated by a : and then followed by a + (to include a country), or - (to exclude a country).

The example above would therefore show the text only to a visitor with an IP address from the USA.

= Can I include or exclude multiple countries using 1 set of tags? =

Yes, see the example below, which excludes both the USA and UK, but shows it to everyone else:

[cbc:-usa:-gb]My example content here.[/cbc]

= Where can I find a full list of country codes that can be used? =

You can view a full list of countries supported, and their codes, from your Wordpress dashboard at Manage -> CBC Content, or by using the link below:

http://www.simonemery.co.uk/wp-plugins/content-by-country/

= Will the database used ever go out of date? =

The plugin uses the GeoLite Country database from maxmind.com, which is updated at the beginning of each month. You can download
an updated database from the link below (you will need the binary version):

http://www.maxmind.com/app/geolitecountry

You would then need to overwrite the GeoIP.dat file in the database folder of the plugin.

== Screenshots ==

1.