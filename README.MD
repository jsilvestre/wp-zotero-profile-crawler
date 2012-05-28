Zotero Profile Crawler - Wordpress plugin
=========================================

*Wordress requirements*
Requires at least: 3.0
Tested up to: 3.3.1

Usage: $zotero_profile_crawler->get_data("<userslug>", Zotero_Profile_Crawler::TYPE_CONTENT||Zotero_Profile_Crawler::TYPE_IMAGE);

Zotero Profile Crawler allows you to retrieve the data from a Zotero CV page (not the API). It is heavily based on https://github.com/scholarpress/scholarpress-vitaware that basically does the same thing, but from the API.
The difference is that Zotero Profile Crawler also retrieves the profile picture. It does not depend on XSLTProcessor. The drawback is that the code is less nice than scholarpress-vitaware :)

It is not usable as a wordpress tag.

Do not hesitate to submit pull requests!