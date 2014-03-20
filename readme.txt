=== Jameel's Dev Tools ===
Contributors: jameelbokhari
Donate link: http://jameelbokhari.com/
Tags: columns, development, tools, url, find and replace
Requires at least: 3.0.1
Tested up to: 3.6
Stable tag: 1.0.2
License: WTFPL
License URI: http://www.wtfpl.net/

Chalked full of little helpers for developing websites, including search and replace function and shortcode to create columns.

== Description ==

This plugin adds a few useful features for when you are developing a website on WordPress. It is intended to be installed on a development server, or local installation when setting up a website and should be left installed after production to retain the shortcode commands.

Features Include:

*    **A find and replace feature** -- to ensure an easy transition from your development site(s) to your live website. Using this function, replace all instances of your website’s url in image tags and links with shortcode for your site’s home url. The find and replace option isn’t just for links and images though, use it to replace any text with something else! *As a precaution*, after searching for a string, you are shown the # of instances and # of posts affected before commiting to your changes.
*    **Dynamic Links** -- `[homeurl]` shortcode, which translates into your website's homepage. This is an essential tool for development. As you add images and internal links, replace your hard-coded links to `[homeurl]` so that when you switch your server over, your links and images still work! When using the visual editor, the plugin will still show images that use `[homeurl]` in their src.
*    **Columns** -- This plugin features columns using shortcode. Use the `[col]` shortcode to wrap your text in a column, always ending with `[/col]` to end the column. Choose to use the plugin's css or style it yourself. Columns can have a range of 12 widths, creating countless possible layouts. By default the width is 6 for two even half-width columns. See FAQ for more info, or go under the Columns tab in the plugin settings for help.

== Installation ==

1. Upload `jameels-dev-tools` entire directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Start using shortcode immediately. See Tools > Jameel's Dev Tools for options and more info.

== Frequently Asked Questions ==

= How can I add columns into pages? =

Use the shortcode `[col]...[/col]` to create a column, replacing `...` with the content. Columns can have different width. The width can be any number between 1 and 12 and uses perecentages. So `12` is full width (with some margin), a width of `6` is half, a width of `4` is a third, and a width of `3` is one fourth. You can create any combination, preferrably if it adds up to 12. There are thousands of combinations you can do.

= Why are my columns uneven? =

If your columns are not level it could be due to the extra line breaks that wordpress adds. You can turn off wpautop in the plugin's general settings OR remove the extra line break. In other words, your columns need to be on the same line. EG:

`[col]Your first column's content...[/col][col]Stil same line, your second column's content...[/col]`

This eliminates extra `<br>` and `<p>` tags in the html output, which can cause the columns to be uneven.

= How does the find and replace tool work? =

The search and replace will query all content from posts and pages at once and then perform the replacement.

**Under Tools**> **Jameel's Dev Tools**, you'll find a tab labeled **Links**. Here is where you fill in the fields to "Change all instances of ___ to ___. By defaults the site's url is set to be replaced with the shortcode `[homeurl]` because this is what it is inteded for.

The changes are saved as separate posts temporarily, and you are reported the number of instances found total and how many posts will be effected. At this point you can choose to save the settings. This has been tested with only a small ammount of pages and posts, but if you have a lot of posts and pages this can potentially take a LONG time (it's only been tested with a small ammount of content). Always, *always*, **always** backup database before performing this action!

= The search and replace tool doesn't do anything, why? =

If you don't see results, it's because you searched for something that wasn't found. I'm working on cleaning up the messages for the next release.

= The plugin aslo doesn't _____? =

I'm working on it.

== Screenshots ==

1. Plugin adds buttons for creating shortcode.
2. Images using `[homeurl]` shortcode in their src will still show up in the Visual Tab
3. Hundreds, if not thousands, of column layouts can be created.

== Upgrade Notice ==

= 1.0.2 =
* Critical update, plugin needs to be deactivated and activated again if installed

= 1.0.0 =
* Initial Release

== Changelog ==

= 1.0.2 =
* [FIX] install function to create table (oops)

= 1.0.1 =
* Documentation

= 1.0.0 =
* Initial Release
