<?php


namespace CategoryCrawl;

require_once('Core.php');
use Core\HtmlCrawler;

class Category{
    
    public function getLinks($url) {
        $htmlCrawler = new HtmlCrawler($url);
        $dom  = $htmlCrawler->getDom();
        // Create an array to store the href and text values
        $links = array();

        // Find all <a> tags with class "site-nav__link" and href starting with "/collections"
        $anchors = $dom->getElementsByTagName("a");
        if (!$anchors) {
            echo "No anchor tags found";
            exit();
        }

        // Find all <a> tags with class "site-nav__link" and href starting with "/collections"
        foreach ($anchors as $a) {
            if (($a->getAttribute("class") === "site-nav__link" || $a->getAttribute("class") === "mobile-site-nav__link") && strpos($a->getAttribute("href"), "/collections") === 0) {
                $href = $a->getAttribute("href");
                $text = $a->nodeValue;
                $links[] = array("link" => $url.$href, "name" => $text);
            }
        }

        return $links;
    }
}
