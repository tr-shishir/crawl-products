<?php
namespace productCrawl;
use Core\HtmlCrawler;

class productCrawl{
    public function productCrawlByUrl($url)
    {
        $htmlCrawler = new HtmlCrawler($url);
        $dom  = $htmlCrawler->getDom();
        

        // Use XPath to find the elements we're interested in
        $xpath = new \DomXPath($dom);

        // Extract the product title
        $titleNode = $xpath->query('//h3[contains(@class, "product-detail__title")]')->item(0);
        $title = $titleNode->textContent;

        // Extract the product price
        $priceNode = $xpath->query('//span[contains(@class, "product-price__reduced")]')->item(0);
        $price = @$priceNode->textContent ?? 0;

        // Extract the product description
        $descriptionNode = $xpath->query('//div[contains(@class, "product__description_full--width")]')->item(0);
        $description = @$descriptionNode->textContent??"N/A";

        // Extract the product image URL

        $images = [];
        $imageElements = $dom->getElementsByTagName('img');
        ;
        foreach ($imageElements as $imageElement) {
            if ($imageElement->getAttribute("class") === "rimage__image") {
                $imageUrl = $imageElement->getAttribute("src");
                if(!in_array($imageUrl,$images)){
                    $images[] = $imageUrl;
                }
            }
        }

        $data = array(
            'title' => $title,
            'price' => $price,
            'description' => $description,
            'images' => $images
        );
        return $data;

        // Output the results
        echo "Title: $title\n";
        echo "Price: $price\n";
        echo "Description: $description\n";
            // echo "Image URL: $imgUrl\n";

       
    }
}

// Load the HTML code into a DOMDocument object


?>
