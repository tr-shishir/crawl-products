<?php
namespace Core;
class HtmlCrawler {
    public $html;
    public $dom;
    public $url;
    
    public function __construct($url) {

        $this->url = $url;

        // Initialize cURL
        $curl = curl_init();

        // Set the website URL to retrieve
        curl_setopt($curl, CURLOPT_URL, $url);

        // Set the user agent
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3");

        // Set return transfer to true
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request and retrieve the HTML response
        $this->html = curl_exec($curl);

        // Close the cURL session
        curl_close($curl);

        // Create a DOMDocument object and load the HTML
        $this->dom = new \DOMDocument('1.0', 'UTF-8');

        // set error level
        $internalErrors = libxml_use_internal_errors(true);

        // load HTML
        $this->dom->loadHTML($this->html);

        // Restore error level
        libxml_use_internal_errors($internalErrors);

        
        
    }
    
    public function getDom()
        {
            return $this->dom;
        }


}