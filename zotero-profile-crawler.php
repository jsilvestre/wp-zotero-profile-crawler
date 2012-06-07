<?php
/*
Plugin Name: Zotero Profile Crawler
Plugin URI: https://github.com/jsilvestre/wp-zotero-profile-crawler
Description: Gets and displays a CV from Zotero.
Authors: Joseph Silvestre
Version: 0.1
Author URI: http://github.com/jsilvestre

Heavily based on https://github.com/scholarpress/scholarpress-vitaware

Usage: $zotero_profile_crawler->get_data("<userslug>", Zotero_Profile_Crawler::TYPE_USERNAME||Zotero_Profile_Crawler::TYPE_CONTENT||Zotero_Profile_Crawler::TYPE_IMAGE);

*/

if ( !class_exists( 'Zotero_Profile_Crawler' ) ) :

class Zotero_Profile_Crawler {

    const TYPE_USERNAME = "type_username";
    const TYPE_IMAGE = "type_image";
    const TYPE_CONTENT = "type_content";
    
    const CV_SECTION_IDENTIFIER = "profile_cvEntry";
    const CV_IMAGE_IDENTIFIER = "profile_cvEntry";
    
    private $_data = array(self::TYPE_USERNAME => "", self::TYPE_IMAGE => "", self::TYPE_CONTENT => array());
    
    function zotero_profile_crawler() {
    }
    
    function parse($user) {

        $path = 'http://www.zotero.org/'.$user.'/cv';

		if($html = file_get_contents($path)) {

			$dom = new DOMDocument();
            if(@$dom->loadHTML($html)) { // the warning suppression is mandatory because the HTML is not well formed

                $divElements = $dom->getElementsByTagName('div');
                $cv = new DOMDocument();
                
                foreach($divElements as $element) {
                    if($element->hasAttribute("class") && $element->getAttribute("class") == self::CV_SECTION_IDENTIFIER) {
                        
                        $childs = $element->getElementsByTagName('div')->item(0)->childNodes;
                        $text = "";
                        foreach($childs as $child) {
                            
                            if($child->nodeName == "p") {
                                if(!empty($text)) {
                                    $text.="</p>";
                                }
                                
                                $text .= "<p>";
                                
                                $grandchilds = $child->childNodes;
                                foreach($grandchilds as $gchild) {
                                    
                                    if($gchild->nodeName == "a") {
                                        $text .= '<a href="'.$gchild->getAttribute("href").'">'.$gchild->nodeValue.'</a>';
                                    }
                                    else {
                                        $text .= $gchild->nodeValue;
                                    }
                                }
                                
                            }
                            elseif($child->nodeName == "a") {
                                $text .= '<a href="'.$child->getAttribute("href").'">'.$child->nodeValue.'</a>';
                            }
                            elseif($child->nodeName == "#text") {
                                $text.= $child->nodeValue;
                            }
                        }
                        
                        $section = array(
                            'title' => $element->getElementsByTagName('h2')->item(0)->nodeValue,
                            'text' => $text
                        );
                        
                        $this->_data[self::TYPE_CONTENT][] = $section;
                    }
                }

                $this->_data[self::TYPE_IMAGE] = $dom->getElementById("profile-picture")->getElementsByTagName("img")->item(0)->getAttribute("src");
                $test = $dom->getElementsByTagName("h1");
                foreach($test as $t) {
                    
                    $name = trim($t->nodeValue);
                    if(!empty($name) && preg_match("#.*Curriculum Vitae#", $name)) {
                        $name = substr($name, 0, strpos($name, ':') - 1);
                        $this->_data[self::TYPE_USERNAME] = $name;
                    }
                    
                }
            }
        }
    }
    
    function get_data($user, $type) {
 
        // we use APC to cache the data
 	    if(!function_exists('apc_cache_info')) {
            if(empty($this->_data[$type])) {
                $this->parse($user);
            }
	    }
	    else {
	        $data = apc_fetch('zotero-profile-crawler-cv-'.$user);
            if($data === false) {
                $this->parse($user);
			    apc_store('zotero-profile-crawler-cv-'.$user, $this->_data, 5);
            }
            else {
                $this->_data = $data;
            }
        }
        
        if(empty($this->_data[$type])) return false;

        return $this->_data[$type];
    }
}

endif;

$zotero_profile_crawler = new Zotero_Profile_Crawler();