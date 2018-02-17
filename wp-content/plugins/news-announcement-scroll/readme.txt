=== News Announcement Scroll ===
Contributors: storeapps, niravmehta, akash123dhawade, Mansi Shah
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CPTHCDC382KVA
Author URI: http://www.storeapps.org/
Tags: News, Announcement, Scroll news,  News plugin WordPress, Scrolling news WordPress,Vertical news, Vertical scrolling news, WordPress dynamic news, WordPress set post or page as news,Custom post type, Free scrolling news widget wordpress plugin, latest news, main news page scrolling, News, news widget, vertical news scrolling widget, widget, WordPress dynamic news, wordpress horizontal news plugin widget,wordpress news plugin, wordpress vertical news plugin widget 
Requires at least: 3.4
Tested up to: 4.7.1
Stable tag: 8.8.4
License: GPLv3

News Announcement Scroll is a simple vertical scroll news widget for your WordPress website. Easy to use & no coding knowledge required.

== Description ==

Every website requires a widget to display important announcements/upcoming events to their audiences. The news scroller keep your audience updated about the latest and the most important happenings on your website while giving you the option of adding link to the news. Using the News Scroller you can share offer updates, new blog updates, sale announcements, contest announcements, latest happenings etc all in one place so that the visitors can find it easily. 

= How it works? =

1. Simply activate the plugin.
2. Create or edit your news groups.
3. Customize the news items (title, font, color etc).
4. Drag and drop the widget into your website’s sidebar/ Use a shortcode to display it on post and pages/ Include it directly within your website’s theme.

= Features of this plugin =

1. Quick and easy installation.
2. Widgets, so you can add pretty much anything.
3. Easy style-override feature.
4. Option to setup news expiration date.
5. Option to add redirect link to the news.
6. You can add N number of news; it will scroll one by one in the front end (vertical scrolling).
7. Divide the news into various groups. Admin can then decide which news group he wishes to display.
8. You can prioritize the order in which the news is displayed.
9. You can customize the scroll direction i.e Down to Up/Up to Down.
10. If you want, you can hide the news temporarily.
11. Responsive admin layout.
12. Shortcode available for pages and posts.
13. Code for adding the widget to your theme.
14. Supports localization.
15. No need of any coding knowledge.
16. Premium support available.

= Plugin configuration =

1. Drag and drop the widget: Go to widget page under appearance tab, drag and drop News announcement scroll widget into your side-bar. It’s that easy.

2. Add the news in the posts or pages: Use the shortcode [news-announcement group="group1"], to add the plugin to your post or pages.

3. Add directly in the theme: Use this code, `<?php if (function_exists ('news_announcement')) news_announcement(); ?>` to add the plugin to your theme file.

= Shortcode information =

Shortcode for version 2.0 to 5.0 : [NEWS:TYPE=widget]

Shortcode for version 6.0 to 8.7.1 : [news-announcement type="widget"]

Shortcode for version 8.8 onwards : [news-announcement group="sample"] 

== Installation ==

= Installation Instruction =

1. Download the plugin news-announcement-scroll.zip.
2. Unpack the news-announcement-scroll.zip file.
3. Extract the /news-announcement-scroll/ folder.
4. Drop the news-announcement-scroll folder into your ‘wp-content/plugins/’ folder.
5. In WordPress administration panels, click on plugin from the menu.
6. You should see your News Announcement Scroll plugin listed.
7. Click Activate.
  
= Plugin configuration =

1. Go to your WordPress dashboard->Settings->News Announcement Scroll and add/edit/delete the announcements.

2. Go to your WordPress dashboard->Settings->News Announcement Scroll->click 'Widget settings', here you can customize your widget styles.

3. Go to your WordPress dashboard->Appearance->Widgets->Drag & drop News Announcement Scroll widget to your desired location in the active sidebar.

== Frequently Asked Questions == 

= Documentation =

Check from here : [Documentation](http://www.storeapps.org/knowledgebase_category/news-announcement-scroll/)

= Can I display more news at same time? = 

Yes, you can add n number of news, it will display one by one at front end (vertical scrolling).

= Can I change the scroll manner from vertical to horizontal? =

No, Only vertical scroll is available.

= Can I display announcements in random order? =

Yes, click Widget settings->find 'Announcement display order' and select 'Random Order' from the dropdown.

= Can I arrange the news scroll order? =

Yes, click on Add New/Edit news in that set 'Enter display order' to the order of your choice e.g 1,2,3 e.t.c. 

= Can I hide any news temporary? =

Yes, change the display order status to 'Yes' in the dropdown.

= Can I change the Slide Direction? = 

Yes, click Widget setting->find 'Slide direction' and select the direction from the dropdown.

= Can I customize the all other appearance style? =  

Yes, click 'Widget settings', here you will find the various customization available for the widget. 

= Why my news content out of range? =

In the front end widget area, if you see any news content out of area or invisible, it is because of height and width of the widget, so you should re-arrange the width and height of the widget in 'Widget settings' to your needs. The default height and width are 180px and 100px respectively.

= How to add News Announcement Scroll widget into pages or posts? =

You can add the widget using the shortcode. You can find shortcode details below.

= How to add News Announcement Scroll widget into my theme? =

Use this code, `<?php if (function_exists ('news_announcement')) news_announcement(); ?>` to add the plugin to your theme file.

= How to add different set of scroll in the same page? = 

No, you cannot have multiple widgets in the same page/post. However, you can add different set of announcement in different pages. For instance suppose you want to display two different news on two different pages/posts you can add the shortcode [news-announcement group="group1"] in the first page and [news-announcement group="group2"] in the second page.

= Shortcode Details = 

Shortcode for version 2.0 to 5.0 : [NEWS:TYPE=widget] 

Shortcode for version 6.0 to 8.7.1 : [news-announcement type="widget"]

Shortcode for version 8.8 onwards : [news-announcement group="group1"]

== Screenshots ==

1. Front Page - News Announcement

2. Admin View

3. Admin Edit View

4. Widget Settings

== Changelog ==

= 8.8.4 (19-1-2017) =

* New: Compatible with WordPress 4.7.1
* Update: Text corrections and UI improvements
* Update: Removed translation files from the languages folder
* Update: 5 star rating link

= 8.8.3 (18-7-2016) =

* New: Feature to add redirect link to the news
* New: Redirect to News Announcement Scroll settings page on plugin activation
* New: Added Docs & Settings link on the plugins page
* Fix: Horizontal and Vertical alignment for news not working
* Update: Text correction in few places
* Update: Updated POT file

= 8.8.2 (23-5-2016) =

* Fix: Removed the dependency between shortcode and widget to be necessarily present in active sidebar because of which shortcode was not working when added in theme file
* Fix: Removed the dependency between php function and widget to be necessarily present in active sidebar because of which php function was not working when added in theme file

= 8.8.1 (12-5-2016) =

* Fix: Code for adding widget to the theme file 
* Update: Tested up to 4.5.2
* Update: Revised readme page
* Update: Added missing shortcode details on the plugin page
* Update: Text correction in few places
* Update: Code indentation
* Update: Updated POT file

= 8.8 (2-5-2016) =

* New: Scripts are now localized and can be translated
* New: New shortcode from v8.8 [news-announcement group="sample"]
* Fix: News display order when shortcode is used.
* Update: Tested up to 4.5.1
* Update: Added POT file
* Update: Updated help & 5-star link
* Update: Revised readme page
* Update: Text correction in few places
* Update: Code indentation

= 8.7.1 (18-12-2015) =

* New contributor has been added.

= 8.7 =

* Tested up to 4.4

= 8.6.1 =

* Text Domain slug has been added for Language Packs.

= 8.6 =

* Tested up to 4.3

= 8.5 =

* Tested up to 4.2.2

= 8.4 =

* Tested up to 4.1

= 8.3 =

* Tested up to 4.0

= 8.2 =

* New option to add publish date for news announcement.

= 8.1 =

* Tested up to 3.9

= 8.0 =

* Tested up to 3.8 (Beta)
* Now this plugin supports localization (or internationalization). i.e. option to translate into other languages. Plugin .po file (newsscroll.po) available in the languages folder. Translators Welcome.
* Supports network multisite website (Needs to create table manually).

= 7.0 =

* Tested up to 3.6
* Added some security feature.
* New admin look.

= 6.0 =

* Tested up to 3.5

= 5.0 =

* New demo link

= 4.0 =

* Tested up to 3.4

= 3.0 =

* Tested up to 3.3
* Javascript included as per WP standard.

= 2.0 =

* Shortcode available  for posts and pages.

= 1.0 =

* First version.

== Upgrade Notice ==

= 8.8.4 (19-1-2017) =

* New: Compatible with WordPress 4.7.1
* Update: Text corrections and UI improvements
* Update: Removed translation files from the languages folder
* Update: 5 star rating link

= 8.8.3 (18-7-2016) =

* New: Feature to add redirect link to the news
* New: Redirect to News Announcement Scroll settings page on plugin activation
* New: Added Docs & Settings link on the plugins page
* Fix: Horizontal and Vertical alignment for news not working
* Update: Text correction in few places
* Update: Updated POT file

= 8.8.2 (23-5-2016) =

* Fix: Removed the dependency between shortcode and widget to be necessarily present in active sidebar because of which shortcode was not working when added in theme file
* Fix: Removed the dependency between php function and widget to be necessarily present in active sidebar because of which php function was not working when added in theme file

= 8.8.1 (12-5-2016) =

* Fix: Code for adding widget to the theme file 
* Update: Tested up to 4.5.2
* Update: Revised readme page
* Update: Added missing shortcode details on the plugin page
* Update: Text correction in few places
* Update: Code indentation
* Update: Updated POT file

= 8.8 (2-5-2016) =

* New: Scripts are now localized and can be translated
* New: New shortcode from v8.8 [news-announcement group="sample"]
* Fix: News display order when shortcode is used.
* Update: Tested up to 4.5.1
* Update: Added POT file
* Update: Updated help & 5-star link
* Update: Revised readme page
* Update: Text correction in few places
* Update: Code indentation

= 8.7.1 (18-12-2015) =

* New contributor has been added.

= 8.7 =

* Tested up to 4.4

= 8.6.1 =

* Text Domain slug has been added for Language Packs.

= 8.6 =

* Tested up to 4.3

= 8.5 =

* Tested up to 4.2.2

= 8.4 =

* Tested up to 4.1

= 8.3 =

* Tested up to 4.0

= 8.2 =

* New option to add publish date for news announcement.

= 8.1 =

* Tested up to 3.9

= 8.0 =

* Tested up to 3.8 (Beta)
* Now this plugin supports localization (or internationalization). i.e. option to translate into other languages. Plugin .po file (newsscroll.po) available in the languages folder. Translators Welcome.
* Supports network multisite website (Needs to create table manually).

= 7.0 =

* Tested up to 3.6
* Added some security feature.
* New admin look.

= 6.0 =

* Tested up to 3.5

= 5.0 =

* New demo link

= 4.0 =

* Tested up to 3.4

= 3.0 =

* Tested up to 3.3
* Javascript included as per WP standard.

= 2.0 =

* Shortcode available  for posts and pages.

= 1.0 =

* First version.