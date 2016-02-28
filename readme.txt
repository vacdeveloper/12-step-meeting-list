=== 12 Step Meeting List ===
Contributors: aasanjose
Tags: meetings, aa, al-anon, na, 12-step, locations, groups
Requires at least: 3.2
Tested up to: 4.4
Stable tag: 1.9.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin is designed to help 12 step programs list their meetings and locations. It standardizes addresses, and displays in a list or map.

== Description ==

This plugin is the easiest way to have your area's meetings listed in the [Meeting Guide mobile app](https://meetingguide.org/) for iOS and Android devices.

It was originally designed to maintain a list of AA meetings in Santa Clara County, CA. It's now in use in the following areas:

**AA**

* [Austin, TX](http://austinaa.org/meetings/)
* [District 15, Chicago, IL](http://d15aa.org/d15aa.org/?post_type=meetings)
* [East Bay, CA](http://eastbayaa.org/meetings)
* [Europe](http://alcoholics-anonymous.eu/index.php/meetings/)
* [Greensboro, NC](http://nc23.org/meetings/)
* [Maine and New Brunswick](http://csoaamaine.org/meetings/)
* [Mesa, AZ](http://aamesaaz.org/meetings/)
* [Minneapolis, MN](http://aaminneapolis.org/meetings/)
* [Oahu, HI](http://oahucentraloffice.com/meetings/)
* [Orlando, FL](http://cflintergroup.org/meetings/)
* [Philadelphia, PA](http://www.aasepia.org/meetings/)
* [Portland, OR](http://home.pdxaa.org/meetings/)
* [Quad Cities, IA / IL](http://aa-qc.com/meetings/)
* [Sacramento, CA](http://aasacramento.org/meetings/)
* [San Jose, CA](https://aasanjose.org/meetings)
* [Western Slope, CA](http://westernsloped22.org/meetings/)

**CoDA**

* [Los Angeles](http://www.lacoda.org/)

**NA**

* [Chinook, CA](http://chinookna.org/meetings/)

**SAA**

* [Indiana](http://indiana-saa.org/?post_type=meetings)

[Let us know](mailto:web@aasanjose.org) if you're using this plugin and would like to be listed here.

= Notes =

* in the admin screen, it's best to use Chrome, because then the time field will be nicest
* The Notes field is for any non-standardized meeting info, such as Basement, or Building C
* Location should be a simple place-name, eg Queen of the Valley Hospital
* Address should only be address, no "Upstairs" or "Building C" or "Near 2nd Ave"
* You can fill in a very basic address and then when you tab away from that field you will see it try to 
standardize the address for you. If you write "1000 trancas, napa" it will return with "1000 Trancas Street, 
Napa, CA 94558, US."

== Installation ==

1. Upload files to your plugin folder.
1. Activate plugin.
1. Enter meetings.
1. The meetings archive should now be displaying data, visit the settings page to locate it. 
1. You may also use the tsml_meetings_get() function inside your template.

== Frequently Asked Questions ==

= My meeting type isn't listed! =
If it's a broadly-applicable meeting type, please [contact us](mailto:web@aasanjose.org) so we can include it for you. 
We want to maintain consistency for the [mobile apps](https://meetingguide.org/), so not all proposals are included.

If you have access to your functions.php, you may add additional meeting types for your area. Simply adapt the following
example to your purposes:

	tsml_custom_types(array(
		'ASBI' => 'As Bill Sees It',
	));
	
Please note a few things about custom types:

1. Be careful with the codes ("ASBI" in the above example) as this gives you the ability to replace existing types. 
1. Note that custom meeting types will not be imported into the mobile app.
1. They are for searching. If you can't imagine yourself searching for a meeting this way, then
it's probably not a type you need. Have you ever searched for a 90-minute-meeting? If not, then it's
probably information that better belongs in the meeting notes.
1. Don't add a type for the default, eg 'Hour Long Meeting' or 'Non-Smoking.' If you do that, then you
have to be careful about tagging every single meeting in order to make the data complete.

= The dropdowns aren't opening! =
Most likely, this is because bootstrap is being included twice. You should add the following to your theme 
so that the TSML's version is removed.

	add_action('wp_enqueue_scripts', function(){
		wp_dequeue_style('bootstrap_css');
		wp_dequeue_script('bootstrap_js');
	});

= Where are my meetings listed? =
Your meetings will be listed on their special WordPress Archive page. Where that is depends on your 
Permalinks setup. The easiest way to find the link is to go to the **Meetings > Import & Settings** page 
and look for the link under "Where's My Info?"

= How can I override the meeting list or detail pages? =
Copy the files from the plugin's templates directory into your theme's root directory. If you're using a 
theme from the Theme Directory, you may be better off creating a 
[Child Theme](https://codex.wordpress.org/Child_Themes). Now, you may override those pages. The 
archive-meetings.php file controls the meeting list page, single-meetings.php controls the meetings 
detail, and single-locations.php controls the location detail.

= Are there any shortcodes? =
Yes, you can use `[tsml_meeting_count]`, `[tsml_location_count]`, and `[tsml_region_count]` to display human-formatted
counts of your entities in your posts. "For example, our area currently comprises [tsml_meeting_count] meetings."

= Are there translations to other languages? =
Currently no, but if someone will volunteer to help with the translating, we will add it.

= I entered contact information into the meeting edit page but don't see it displayed on the site. =
That's right, we don't display that information by default for the sake of anonymity. To display it in your 
theme, you should follow the instructions above for overriding the meeting detail and location detail pages 
and then drop some or all of these tags in your PHP:

	<?php echo $meeting->contact_1_name?>
	<?php echo $meeting->contact_1_email?>
	<?php echo $meeting->contact_1_phone?>
	
== Screenshots ==

1. Edit meeting
1. Edit location
1. Meeting detail page
1. Meeting list page
1. Meeting map

== Changelog ==

= 1.9.5 =
* Location name in map infowindow links to location page
* Location and meeting template pages work better with apostrophes

= 1.9.4 =
* Meeting search-by-type bug identified by Central Florida Intergroup

= 1.9.3 =
* Improved printing style

= 1.9.2 =
* Better theme override slug-finding

= 1.9.1 =
* Twenty-fourteen theme overrides for NEFL

= 1.9 =
* Location details bug fix and address-field javascript fix

= 1.8.9 =
* Fixing map bug

= 1.8.8 =
* Now displaying sub-regions in the meeting list table

= 1.8.7 =
* Optimized sql upgrade to avoid out of memory error

= 1.8.6 =
* New optional group object! Location contacts move over to it (Greensboro, New York and Europe)
* Refactored javascript on public page to speed up large meeting lists
* Improved method of linking to assets, fixing issue with Twenty Fourteen (Oahu)
* Users can only select one type at a time now, for security and simplicity (Austin)
* Get group contacts' email addresses quickly (Europe)
* Search for regions (Europe)
* Import sub regions (Maine)
* Correcting a problem with the database upgrader
* Adding some new FAQs

= 1.8.5 =
* Importer no longer verifies Google's SSL certificate for Corpus Christi

= 1.8.4 =
* Meeting-, location- and region-count shortcodes per SEPIA

= 1.8.3 =
* Preventing API header error encountered by East Bay

= 1.8.2 =
* Dropping PHP version requirement, should be compatible with all versions now
