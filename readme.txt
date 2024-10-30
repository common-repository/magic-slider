=== Magic Slider ===
Contributors: webvitaly
Donate link: http://web-profile.net/donate/
Tags: slider, slide, slideshow, carousel, responsive, bxslider, jquery, magic, photo, image, horizontal, vertical, fade
Requires at least: 4.0
Tested up to: 5.5
Stable tag: 1.3
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html


Responsive and flexible slider.

== Description ==

* **[Magic Slider](http://web-profile.net/wordpress/plugins/magic-slider/ "Magic Slider")**
* **[Donate](http://web-profile.net/donate/ "Donate")**
* **[WordPress plugins](http://web-profile.net/wordpress/plugins/ "WordPress plugins")**



Magic Slider features:

* Responsive slider based on bxslider jQuery plugin
* Plugin has basic settings which can be overwritten via params
* `[magic_slider]` shortcode with params
* `magic_slider_output()` php function with params


= Shortcode usage: =

`[magic_slider height="400" category="24" mode="fade" speed="500" auto="1" pause="4000" pager="1"]`

= PHP usage: =

`<?php
$magic_slider_settings = array(
	'height' => 400,
	'category' => 24,
	'mode' => 'fade',
	'speed' => 500,
	'auto' => 1,
	'pause' => 4000,
	'pager' => 1
);
if (function_exists('magic_slider_output')) {
    echo magic_slider_output( $magic_slider_settings );
}
?>`


== Installation ==

1. install and activate the plugin on the Plugins page
2. add couple slides in the admin section
3. add shortcode [magic_slider] to page content


== Frequently Asked Questions ==

= Which library Magic Slider is powered by? =

Magic Slider is powered by [bxSlider - responsive and flexible jQuery slider](http://bxslider.com/).

= How can I sort slides? =

Sliders are sorted by published date in descending order.
You need to change published date to change the order of the slides.

= Which browsers are supported? =

All modern browsers and IE9+ are supported.
Magic Slider is working on mobile devices too.



== Screenshots ==

1. Magic Slider preview.

2. Adding Magic Slider shortcode to the page or post content.

3. Magic Slider Settings.


== Changelog ==

= 1.3 =
* code refactoring

= 1.2 =
* added translation support
* added 'revisions' section
* added dropdown for mode setting
* minor bugfixing and refactoring
* change default sorting from slug to date

= 1.1 =
* updated styles
* added screenshots
* added banner

= 1.0 =
* initial release