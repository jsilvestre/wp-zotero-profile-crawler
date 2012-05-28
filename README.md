Zotero Profile Crawler - Wordpress plugin
=========================================

**Wordress requirements**

Requires at least: 3.0
Tested up to: 3.3.1

**Usage**

```php
<?php
$zotero_profile_crawler->get_data("<userslug>", Zotero_Profile_Crawler::TYPE_USERNAME||Zotero_Profile_Crawler::TYPE_CONTENT||Zotero_Profile_Crawler::TYPE_IMAGE);
?>
```
Where <userslug> is the user's login. You can try with my profile "jsilvestre".

The best way to use it is to make a template page in your Wordpress theme and to build a script around it so you can display the information the way you like.

Zotero Profile Crawler allows you to retrieve the data from a Zotero CV page (not the API). It is heavily based on https://github.com/scholarpress/scholarpress-vitaware that basically does the same thing but from the API.
The difference is that Zotero Profile Crawler also retrieves the profile picture and it does not depend on XSLTProcessor. The drawback is that the code is less nice than scholarpress-vitaware :)

Also, this is not usable as a wordpress tag so you it is not an install&use plugin.

Do not hesitate to submit pull requests!