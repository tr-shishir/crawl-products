<?php

require_once('categoryCrawl.php');
require_once('productCrawlByUrl.php');


// function to crawl product URLs from a given page URL

use Core\HtmlCrawler;


class ProductCrawl{

    public $parent_url;
    public function crwalProducts($url)
    {
        $this->parent_url = $url;
        $category = new CategoryCrawl\Category();
        $links = $category->getLinks($url);
        // var_dump($links);
        //     die;

        foreach( $links as $link){

            // base URL of the website
            $baseUrl = $link['link'];
            
            // array to store all the product URLs
            
            // loop through multiple pages with pagination
            $totalPages = (int)$this->getTotalPage($link['link']);


            for ($page = 1; $page <= $totalPages; $page++) {  // crawl first 10 pages
                $url = $baseUrl . '?page=' . $page;
                $urls = $this->crawlProductUrls($url);
                try{
                    $this->saveToCsv($urls,$link);

                }catch(Exception $e){

                    print $e;

                }
            }
        }
    }

    function getTotalPage($url){
    
            $htmlCrawler = new HtmlCrawler($url);
            $dom  = $htmlCrawler->getDom();

    
            // $dom->load($dom);
            $finder = new DomXPath($dom);
            $classname="page";
            $nodes = $finder->query("//*[contains(@class, '$classname')]");
            $totalPage=0;
            
            foreach ($nodes as $a) {
                if ($a->getAttribute("class") === "page") {
                    $totalPage = ($totalPage<$a->nodeValue)?$a->nodeValue:$totalPage;
                }
            }
            return $totalPage;
    
    }
    
    
    function crawlProductUrls($url) {
        $htmlCrawler = new HtmlCrawler($url);
        $dom  = $htmlCrawler->getDom();
        $xpath = new DOMXPath($dom);
        $urls = array();
        $nodes = $xpath->query("//a[contains(@class, 'product-block__title-link')]");

        foreach ($nodes as $node) {
            if(!in_array($node->getAttribute('href'),$urls)){
                $urls[] = $this->parent_url.$node->getAttribute('href');
            }
          
        }
        return $urls;
    }

    function saveToCsv($urls,$link){
        $next_id = count(file('products.csv'))??1;
        // $next_id = 1;
        $file = fopen('products.csv', 'a');
        if($next_id <= 1){
            fputcsv($file, ['id','title', 'description', 'Category', 'price', 'url', 'images']);
            $next_id = 1;
        }
        foreach($urls as $url){
            $productCrawl = new productCrawl\productCrawl();
            $data = $productCrawl->productCrawlByUrl($url);
            fputcsv($file, [$next_id, $data['title'], preg_replace('/\s+/', ' ', $data['description']), $link['link'], preg_replace('/\s+/', ' ', $data['price']), $url, implode(',',$data['images'])]);
            $next_id++;
            echo '\r\n';
            echo $data['title'].'\r\n';

        }
        fclose($file);
    }
    
}

?>
