=== Gallerio ===

Contributors: subhasis005

Tags: gallery, image, showcase, gallerio, simple, colorbox, lightbox, canvas, pagination gallery, latest powerful gallery, light weight gallery,
gallery lightbox, thumbnail gallery, multiple gallery, wordpress gallery simple, multiple upload gallery

Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=subhasislaha%40rediffmail%2ecom&lc=US&item_name=Donation%20for%20Gallerio&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest

Requires at least: 3.8

Tested up to: 4.4.2

Stable tag: 1.0.1

License: GPLv3

License URI: http://www.gnu.org/licenses/gpl-3.0.html


A simple yet beautiful and powerful gallery plugin for Wordpress where you can create multiple galleries and upload multiple photos to them.


== Description ==

= What is Gallerio? =

Tired of finding a suitable gallery plugin for your website? Here is your solution. Gallerio is a simple beautiful gallery plugin for Wordpress where you can create multiple galleries and upload multiple photos to each of the galleries you create. You can also show the galleries in a single page by using specific shortcode available. Gallerio serves your purpose like no other plugin can. It's simple user interface and settings can make our gallery appear as you actually wish. You can resize your gallery thumbnails at ease and make your gallery pictures appear the way you actually wish without any quality loss of your images. In one word, this is the most simple yet powerful way of creating your image showcase out of the box.

= Features =

* Sharp thumbnail creation of gallery images without any quality loss
* Multiple upload of images in gallery
* Update picture title, alt and link information in single shot
* Different dimension options for the picture thumbnails
* Pagination support
* Regenration of thumbnails after changing dimensions in one click
* Embedding multiple galleries in one single page or post including pagination
* Edit gallery information by the embedded Wordpress default text editor

= Screenshots =

View the <a href="http://wordpress.org/plugins/gallerio/screenshots/">Screenshots</a> to have a quick glance of the plugin before installation.

= Installation =

Read the <a href="http://wordpress.org/plugins/gallerio/installation/">Guide</a> here.

= FAQ =

Read the <a href="http://wordpress.org/plugins/gallerio/faq/">FAQ</a> if you have any question.

= Support =

For further queries feel free to drop a line at <a href="mailto:subh.laha@gmail.com">subh.laha@gmail.com</a>. Will try to get back to you in notime. 

= Live Demo =

Click on the link to see the <a href="http://subhasislaha.com/projects/demoblog/gallerio-demonstration/" target="_blank">Live Demo</a>


== Installation ==

= Automatic Installation =

* Go to your plugin browser inside your wordpress installation and search `Gallerio` by keyword. Then click install and it will be installed shortly.
* Activate the plugin from `Plugins` menu after installation

= Manual Installation =

* Download the latest version and extract the folder to the `/wp-content/plugins/` directory
* The plugin will appear as inactive in your `Plugins` menu
* Activate the plugin through the `Plugins` menu in WordPress


== Usage ==

 = Show specific gallery =

   Just paste this shortcode `[gallery id="1"]` in your post or page to get the gallery pictures. Here `1` is the id of the gallery.

 = Show all galleries =
   
   Just paste this shortcode `[galleries ids="all"]` in your post or page to get all galleries you have created.

 = Show specific galleries =
   
   Just paste this shortcode `[galleries ids="2,3"]` in your post or page to get the specific galleries you have created. Here `2` & `3` are the    specific ids of the galleries.


== Frequently Asked Questions ==

= 1. Can I include mulliple gallery shortcode in a single page or post? =
   
   Yes, you can. You can include multiple gallery shortcodes in a single page or post flawlessly. Even if pagination is enabled you will be able to use pagination links simultaneously for those galleries.
   
= 2. In case of bulk upload only 20 files are getting uploaded once. How can I increase this limit? =

   Yes, this can be done from your server end. Your apache php configuration variable `max_file_uploads` might have been set to only 20. You can change this in your php configuration to increase this limit. If you    can't contact your server administrator to do so.
   
= 3. Can I modify the gallery canvas size or the thumbnail size that will be shown in my page or post? =

   Yes, you can. All of the thumbnail and gallery related settings can be found in `Settings` section of this plugin.
   
= 4. The colorbox jQuery library is having conflict with my existing theme jQuery library. I want to implement my own lightbox plugin. Can I do so? =

   Yes, you can. You can disable colorbox in Settings and integrate your own lightbox plugin to avoid the conflict. In that case you may have to modify some plugin codes to match the gallery structure with your lightbox plugin.
   
= 5. Can I link some external URL to one of my gallery pictures? =

   Yes, you can. You can link some external URL to one of your gallery pictures. In that case just you have to turn off colorbox and set a link to that image.  
   
= 6. I have changed my thumbnail and picture dimensions after uploading photos. Will I have to upload them again to have the change? =

   No you don't have to upload your pictures again. All you have to do is regenerate the thumbnails from your settings menu. Just go to settings and click on regenerate thumbnails. Wait for a while and all your thumbnails will be regenerated according to the new dimensions you have set. This can take several minutes also depending on the quantity of pictures you have in your galleries.
   

==  Screenshots ==

1. Gallerio plugin home page.
2. List of the galleries created in admin.
3. Gallerio settings panel
4. Single/Bulk photo upload panel of a particular gallery.
5. Gallery preview in page or post by shortcode.
6. Gallery preview with pagination.
7. Colorbox in a gallery.
8. All galleries preview inside a page or post.
9. Regenerate thumbnails.

== Changelog ==

= 1.0.0 =

* Initial release

= 1.0.1 =

* Minor bug fixes

== Upgrade Notice ==

* Initial release