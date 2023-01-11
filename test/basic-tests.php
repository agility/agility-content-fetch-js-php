<?php
require_once(".." . '/vendor/autoload.php');


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();


$apiKey = $_ENV["AGILITY_API_PREVIEW_KEY"];
$guid =  $_ENV["AGILITY_GUID"];
$apitype = "preview";  //or fetch...
$locale = "en-us";


// Configure API key authorization: APIKeyAuthorization
$config = Agility\Client\Configuration::getDefaultConfiguration()->setApiKey("APIKey", $apiKey);

// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Agility\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('APIKey', 'Bearer');

$apiList = new Agility\Client\Api\ListApi(new GuzzleHttp\Client(), $config);
$apiItem = new Agility\Client\Api\ItemApi(new GuzzleHttp\Client(), $config);
$apiPage = new Agility\Client\Api\PageApi(new GuzzleHttp\Client(), $config);
$apiSitemap = new Agility\Client\Api\SitemapApi(new GuzzleHttp\Client(), $config);


$apiInstance = new Agility\Client\Api\ContentModelsApi(
    new GuzzleHttp\Client(),
    $config
);


try {

    $sitemapFlat = $apiSitemap->getSitemapFlat($guid, $apitype, $locale, "website");

    //loop all the pages and break after the first page...
    foreach ($sitemapFlat as $i => $value) {
       // $pageID = $value->pageID;
        print_r("First sitemap entry: $i");
        print_r("- pageID:");
        print_r($value->pageID);
        print_r("\n");
        break;
    }

    print_r("\n");

    $sitemapNested = $apiSitemap->getSitemapNested($guid, $apitype, $locale, "website");

    print_r("Nested sitemap: first page id:");
    print_r($sitemapNested[0]->pageID);

    print_r("\n\n");

    $page = $apiPage->getPage($guid, $apitype, $locale, 2);

    print_r("get page $page->pageID \n");

    print_r("first module in MainCOntentZone: ");
    print_r($page->zones->MainContentZone[0]->item->contentID);

    print_r("\n\n");

    //ITEM TEST
    $item = $apiItem->getContentItem($guid, $apitype, $locale, 199);

    print_r("get Item $item->contentID \n\n");

    //MODEL TEST
    // $result = $apiInstance->guidApitypeContentmodelsGet($guid, $apitype);

    //LIST TEST
    $result = $apiList->getContentList($guid, $apitype, $locale, "posts");

    $totalCount = $result->totalCount;
    $item = $result->items[0];
    $itemFields = $item->fields;
    $itemProps = $item->properties;

    print_r("Get List $totalCount \n");
    print_r("\n\n");



    //print_r("Resp: $item->fields->title \n");
} catch (Exception $e) {
    echo 'Exception when calling ContentModelsApi->guidApitypeContentmodelsGet: ', $e->getMessage(), PHP_EOL;
}
