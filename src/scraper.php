<?php
// initialize the cURL request
require_once __DIR__ . "../../vendor/autoload.php"; 
use voku\helper\HtmlDomParser;

$curl = curl_init();
// set the URL to reach with a GET request
curl_setopt($curl, CURLOPT_URL,"https://scrapeme.live/shop/");
// get the data returned by the cURL request as a string
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// make the cURL request follow eventual redirects, 
// and reach the final page of interest 
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
// excute cURL and get the HTML page as a string
$html = curl_exec($curl);
// release cURL resources
curl_close($curl);
// initialize HtmlDomParser 
$htmlDomParser = HtmlDomParser::str_get_html($html);


// retrieve the HTML pagination elements with 
// the ".page-numbers a" CSS selector 
$paginationElements = $htmlDomParser->find(".page-numbers a");
$paginationLinks = [];
foreach ($paginationElements as $paginationElement) {
    // populate the paginationLinks set with the URL 
	// extracted from the href attribute of the HTML pagination element 
    $paginationLink = $paginationElement->getAttribute("href");
    // avoid duplicates in the list of URLs
    if(!in_array($paginationLink, $paginationLinks)){
        $paginationLinks[] = $paginationLink;
    }
}
// Print the paginationLinks array
print_r($paginationLinks);

// remove all non-numeric characters in the last element of the paginationLinks
// array to retrieve the highest pagination number
$highestPaginationNumber = preg_replace("/\D/", "", end($paginationLinks)); 
print_r($highestPaginationNumber);

$productDataLit = array();
// retrieve the list of products on the page
$productElements = $htmlDomParser->find("li.product");
foreach ($productElements as $productElement) {
    // extract the product data
    $url = $productElement->findOne("a")->getAttribute("href");
    $image = $productElement->findOne("img")->getAttribute("src");
    $name = $productElement->findOne("h2")->text;
    $price = $productElement->findOne(".price span")->text;

    $productData = array(
        "url" => $url,
        "image" => $image,
        "name"=> $name,
        "price" => $price
    );
    
    $productDataList[] = $productData;  
    print_r($productDataList);
}

