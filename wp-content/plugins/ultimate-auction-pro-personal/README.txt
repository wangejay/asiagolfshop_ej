=== Ultimate Auction Pro ===
Contributors: nitesh_singh
Donate link: http://auctionplugin.net/
Requires at least: 3.5
Tested up to: 4.1
Stable tag: 4.4.0

Awesome plugin to host auctions on your wordpress site and sell anything you want.

== Description ==

**Ultimate Auction Pro** plugin allows easy and quick way to set up a professional auction website in ebay style.

Simple and flexible, Ultimate Wordpress Auction plugin works with any WordPress theme. 
Lots of features, very configurable.  Easy to setup.  Great support.

Features:

 = Core Features =
    1. Registered User can place auctions on your website.
	2. Admin can charge listing fee to activate user auctions on per auction basis.
	3. Admin can charge commission on final bid price on user's auction.
	4. Registered User can place bids 
	5. Ajax Admin panel for better management.
    6. Add standard auctions for bidding
    7. Buy Now option with paypal
    8. Upload multiple product images
    9. Show auctions in your timezone        
    10. Paypal ready payment settings
    11. Set Reserve price for your product
	12. Set Bid incremental value for auctions
	13. Ability to edit, delete & end live auctions
	14. Re-activate Expired auctions
	15. Email notifications to bidders for placing bids
    16. Email notification to Admin for all activity
    17. Email Sent for Payment Alerts
    and Much more...
	
 = Display Features =
    1. Auction feed Page which shows excertps of live auctions & groups them in 3 different tabs like "New Listings", "Most Active" and "Ending Soon"
    2. Pagination feature for feed page
    3. Professionally styled Dedicated auction page via wordpress custom post
    4. Comments section for each auction page
	5. Send Private Message section for each auction page
	6. Tested with multiple Wordpress theme


== Installation ==

 = IMPORTANT = 

Please backup your wordpress database before you install/uninstall/activate/deactivate/upgrade Ultimate Wordpress Auction Plugin.

Manual Installation

1. This instruction is for those who are already using FREE version (Wordpress auction plugin) - Go to Dashboard -> Installed Plugins -> Search for "Wordpress Auction Plugin" and  Deactivate it. NOTE: You'll retain all your previous data.

2. Upload the `ultimate-auction-pro` folder to the `/wp-content/plugins/` directory

3. You need to enter your license key. Please refer to this video tutorial: https://auctionplugin.zendesk.com/entries/24975256-Step-1-How-To-Install-Ultimate-Auction-Pro

4. Activate the plugin through the 'Plugins' menu in WordPress

5. Visit Settings tab of the plugin and enter "payment settings" and general settings.

6. Go to "Pages" inside your admin dashboard and add a new page.

7. Enter this text "[wdm_auction_listing]" as a shortcode inside this new page and publish it. 

8. Add another new page and enter text "[wdm_user_dashboard]" as a shortcode inside this page and publish it.

9. Grab permalink of both these new pages

10. Go to Ultimate Auction Pro -> Settings and enter perma link (for page having shortcode "[wdm_auction_listing]") inside "Auction Page URL" field.

11. Go to Ultimate Auction Pro -> Settings and enter perma link (for page having shortcode "[wdm_user_dashboard]") inside "Front End Dashboard URL" field.

12. After you have setup your settings you can go to "Add Auction" tab to add your auction.

13. Access Wordpress page added in step 6 to see your auctions. This page is responsible for displaying all live auctions. If you click a specific auction on this page, it'll open specific auction page where your visitors can place bids and perform all actions related to that auction.


== Screenshots ==


== Frequently Asked Questions ==

== Changelog ==
= 4.4.0 = 
* New Feature - Admin can now customize emails sent by PRO. 
* New Feature - Admin/auctioneer can add a document with their auction. Visitors/bidders can download to know more about the auction.
* New Feature - Admin can add new custom fields to add auction form. To add custom fields you need to go to Settings -> Auction -> Custom Fields -> Add new fields. Once added they will automatically appear inside Add auction form.
* New Feature - PRO when activated will automatically create shortcode ready wordpress pages.
* Fix - Image names having spaces were not uploading. This has now been fixed.
* Fix - PRO was enabling Subscriber role to upload files which by default is not permitted. This problem has now been fixed.
* Fix - User deletion will now delete all corresponding auctions.
 
 
= 4.3.1 = 
* Fix - Warning fixes which was stalling Settings page of the plugin.
* Fix - When paypal email is not set then buy now transaction does not happen. Added a error message which would notify user about it.


= 4.3.0 = 
* New Feature - Best Offer: This feature would let users send their best offers for auctions to auctioneer. Auctioneer can accept/reject them. On acceptance, auction will expire asking for payment from the user.
* New feature - Display individual categories - With the help of shortcode format: [wdm_auction_listing ua_cat=CATEGORY NAME], you can display auctions of that specific categories. This would come in handy if you want to create separate page and hyperlink that page in your site.
* New Feature - Admin can now decide if auctioneer gets the power to use either bidding engine while adding auction. Admin can block auctioneer to edit bidding engine and force them to use the one admin wishes them to use.
* New Feature - Currencies not supported by Paypal can now be used for auctions but with manual payment methods/
* Fix - Currencies now are formatted with proper comma/
* Fix - Few warning fixes.

= 4.2.0 = 
* New Feature - Digital Auctions would let auctioneer auction documents. It has the ability to upload document, bid for them and once bidder wins, share documents.
* Fix - Performance improvements were made for plugin.

= 4.1.1 = 
* Fix - Feed page -> Categories 1st drop down has been renamed as Shop by category
* Fix - Feed page -> Categories 1st drop down formatting errors like it now only shows 9 categories with Show all categories link. This has been done so that drop down does not grow big if there are many categories.
* Fix - WP admin panel -> Ultimate Auction Pro -> Settings -> New categories link has been added for better visibility and configuration.


= 4.1.0 =
* New Feature - Display Categories on a WP Page. You need to add a new WP Page and insert a new categories shortcode in it. Grab page's url and enter it inside Settings -> Auction -> Category Page Settings.
* New Feature - Set how many auctions you want to show on feed page. Added param under Settings -> Auction -> How many auctions should list on one feed page?. 
* New Feature - 'My watchlist' section in the back end dashboard for non admin users.
* New Feature - Bidder details like first name, email is now available inside Expired auction -> Expiry reason column. 
* New Feature - Allows localhost url on add auction page.

* Fix - Increased Image and font size on feed page for better visibility.
* Fix - Bulk Import fix where CDN image url were not correctly identified. 
* Fix - Paypal OAuth error messages appeared while activating PRO. This has been resolved.
* Fix - Sidebar widget links were not working correctly when widget appeared in pages not relate to auction page.
* Fix - Few customers were getting repeated emails due to interation with paypal services. This issue has been fixed.


= 4.0.4 = 

* Fix - HTML structure for feed page was missing proper tags
* Fix - Widget links are not working on other pages.


= 4.0.3 = 
* Fix - Bulk Import Start date was not working correctly. This has been fixed now.
* Fix - Rename OAUTH inside PRO plugin as it was conflicting some WP themes as they used same variable names.
* Fix - Multiple emails were being sent to auctioneer when their auction was sold. Extra condition has been added to rectify this.


= 4.0.2 = 
* Fix - Buy Now not working correctly for non-paypal payment methods. There is a paypal warning appearing when paypal option has not been selected inside Settings tab.


= 4.0.1 = 
* New Feature - Now you can control how many auctions to list inside feed page. Added new config inside Settings -> Auction -> How many auctions should list on one feed page? Choose your count.
* Fix - Payments tab in Front End Dashboard is realigned to second position to main consistency with WP admin panel. 
* Fix - Red Note saying ....You'll be able to choose only PayPal for getting the payment as admin has activated commissions feature.... will only show when commissions feature is active.


= 4.0.0 =
* New Feature - Sidebar widget to list auctions based on new listings, ending soon, most active and just sold.
* New Feature - Bulk Import to add many auctions together.
* New Feature - Seller Rating and Reviews. Now, users can post rating and reviews for a specific seller.
* New Feature - New payment method called Cash has been added.
* New Feature - User can now use all payment methods to get payments for their auctions. Previously user can add auction using only paypal payment method.
* New Feature - Delete option for Invoices. This would delete invoice listed in plugin.
* New Feature - Delete auction would now delete its images too.
* Fix - Commissions feature's implementation has been changed. Plugin now uses Paypal adaptive APIs to divide money between seller and admin. This is more authentic way of handling commissions feature. 
* Fix - Expired Auction would now show payment method for each auction for better clarity.
* Fix - Email sent by plugin contained html code. This has been fixed.


= 3.6.0 =
* Fix - Buy Now transactions were not working for auctions made by multiple Admins.  PRO settings should apply the same for all admin but it was not. This is now fixed and Buy Now will work for all auctions for all admins.


= 3.5.0 = 

* Fix - Javascript conflict issues on auction dedicated page were resolved.
* Fix - Mandatory fields relevant for shipping/postage feature were not appearing on the backend -> add user form. This prevented adding users from the backend.

= 3.4.0 = 

* Fix - Paypal invoice for auctions created by admin are not getting generated if Fee -> commissions fee is disabled.


== Changelog ==
= 3.3.0 = 

* New Feature - Ability to cancel last bid for auction.
* Fix - Proper Alignment for shipping info link and "Add to watchlist" 

= 3.2.0 = 

* Fix - Add to Watchlist link was appearing only when Buy Now option was availabnle. This has been fixed and now alignment of "Shipping Info" and "Add to watchlist" is good.


= 3.1.0 = 
* New Feature - Add to Watchlist feature. 
* New Feature - Auto Extend Time to avoid snipping now handles both hours and minutes.
* New Feature - Setting to control the display of "Comments" and "Send Private Message" on dedicated auction page.
* Edit - Plugin's Settings tab has been re-organized for better clarity to admin.
* Fix - Auction owner cannot place bid on his own auction
* Fix - Feed page overlap issue for few WP themes.
* Fix - Timer is now localized to be converted to local language.
* Fix - Popup message saying "ʺyou can be winner if you amount is close to buy nowʺ is now rectified to show at correct time.


= 3.0.0 = 
* New Feature - Proxy Bidding Engine. This would let all users place proxy bidding.
* New Feature - Automatic Time Extension to avoid snipping.
* Fix - Feed Page Image is now displayed by scaling it ratio wise which does not squeeze or blur the image.
* Fix - Default Image when no images are loaded.
* Fix - Login/Register pop-up is now bigger to avoid scrolling.

= 2.0.2 = 

* New Search feature with which you can search through your live auctions.
* Just Sold auction tab on auction feed page which lists all auctions sold recently.
* Support for custom registration & login page - PRO pops up login and registration buttons which redirects users to default WP login/register page. If your website has custom pages with custom urls then you can mention these urls inside Plugin -> settings and then popup buttons would open up your custom urls.
* Front End Dashboard now shows proper tabs to users based on their roles.
* Auction short description field - New field added inside "Add Auction" form. This field is responsible in displaying auction excerpts (1 or 2 lines about auction) on feed page. Prior to this, 
* All prices on front end would display decimal values upto 2 places.
* Bug - Shipping Cost is not working for check or wire transfer payment method.
* Bug - Fix provided for HTML Editor for auction description to accept new line characters.
* Bug - Email Sent via plugin would have sender name as website name.

= 2.0.1 = 

* Ability to add more images: Plugin has been enhanced for auctions to include more images for it. Nice Slider appears to browse through multiple images.
* Future Start Date for Auctions: This features lets you draft your auction and specify future start date for it to go live. Plugin will automatically make it live on the specified date.
* Resolution to Image Issue: Imqage inside dedicated auction page is now shrinking in correct ratio to maintain image clarity and prevent it to stretch and get blurred.


= 2.0.0 = 

* Categories feature Added. Refer to installation instruction for it.
* Added Countdown timer for auctions.
* Breadcrumb added for dedicated auction.
* Bid Now button added on feed page.


= 1.0.4 = 

Missing folder for lightbox feature was added.


= 1.0.3 = 

* New Feature - Front End User Dashboard - All your registered users can add/manage auctions, bids from the front end interface of Ultimate Auction Pro. There is no need for your user to go to backend admin dashboard.
* New Feature - HTML editor added for Product description field.
* New Feature - Bulk delete feature added for Manage Auction.
* New Feature - Video Upload are possible now. Lightbox feature added for image/video.
* Feed page Shortcode Issue resolved: Use your own text below and above shortcode.
* Resolved plugin conflicts: Renamed common variables which causes issues with other loosely coded plugins.
* Bug Resolved pertaining to End Auction when 2 bidders are competing for auction till last minute.

= 1.0.2 = 

New Setting For Commissions Feature - Now, admin can charge commissions for which he will be required to moderate the payments for auction owners OR can choose not to charge any commission and stay out of payment loop and connect auction owner & final bidder to handle payments by themselves.

Bug Fixed - Time was not getting displayed for ending an auction.


= 1.0.1 =
Added Decimal pricing for placing bids.

Added Shipping Cost/Postage feature to display shipping cost for auctions.

New Outbid email which is sent to all existing bidders if their bid has been defeated.


= 1.0.0 =
Beta Launch
