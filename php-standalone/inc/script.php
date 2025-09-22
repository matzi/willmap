<script>
<?php 

$prefix_mmo = 'https://cache.willhaben.at/mmo/';
$prefix_willhaben = 'https://willhaben.at/iad/';

//TODO option: &page=2 , &ESTATE_SIZE/LIVING_AREA_FROM=80 & &PRICE_TO=500

if(!isset($_GET["art"])){
    $_GET["art"]= "0";
}
if(!isset($_GET["pages"])){
    $_GET["pages"]= "1";
}
if(!isset($_GET["minsize"])){
    $_GET["minsize"]= "50";
}

switch($_GET["art"]){
    default:
    case "0":
        $art = 'mietwohnungen';
        break;
    case "1":
        $art = 'eigentumswohnungen';
        break;
    case "2":
        $art = 'haus-kaufen';
        break;
}

# FIXME no user input sanitization!
$location = 'steiermark/graz';
if(isset($_GET["district"]) && strlen($_GET["district"]) > 3) {
    $location = $_GET["district"]; 
}
$price_from = (isset($_GET["price_from"]) && intval($_GET["price_from"]) > 0) ? ("&PRICE_FROM=" . intval($_GET["price_from"])) : "";
$price_to = (isset($_GET["price_to"]) && intval($_GET["price_to"]) > 0) ? ("&PRICE_TO=" . intval($_GET["price_to"])) : "";
$area_from = (isset($_GET["area_from"]) && intval($_GET["area_from"]) > 0) ? ("&ESTATE_SIZE/LIVING_AREA_FROM=" . intval($_GET["area_from"])) : "";
$area_to = (isset($_GET["area_to"]) && intval($_GET["area_to"]) > 0) ? ("&ESTATE_SIZE/LIVING_AREA_TO=" . intval($_GET["area_to"])) : "";
$sort = "";
if (isset($_GET["sort"]) && in_array($_GET["sort"], ["1", "2", "3", "4"])) {
    $sort = "&sort=" . $_GET["sort"];
}

$url = "https://www.willhaben.at/iad/immobilien/$art/$location?rows=200";

$url .= $price_from . $price_to . $area_from . $area_to . $sort;

//$default = 'https://www.willhaben.at/iad/immobilien/haus-kaufen/steiermark/graz?rows=200&sort=3';

$js = "var markers = L.markerClusterGroup();\n";

$pattern = "/{\"props\":.*}/i";

$cnt=0;
for($page=1;$page < $_GET["pages"] + 1 ; $page++) {
    # echo "Loading page $page\n";
    # echo $url . "&page=".$page . "\n";
    
    $data = file_get_contents($url . "&page=".$page);
    preg_match($pattern, $data, $matches);
    $data = json_decode($matches[0]);

    foreach ($data->props->pageProps->searchResult->advertSummaryList->advertSummary as $item) {
        $info = array('ADDRESS'=>'NA');
        foreach ($item->attributes->attribute as $entry){
            if (in_array($entry->name , array('MMO','PRICE','ESTATE_SIZE','ADDRESS','LOCATION','COORDINATES','HEADING','POSTCODE','ESTATE_SIZE/LIVING_AREA','SEO_URL', 'PUBLISHED'))){
                $info[$entry->name] = addslashes($entry->values[0]);
            }
        }
        try{
            if(strlen($info['COORDINATES']) > 3 && $_GET["minsize"] <= $info['ESTATE_SIZE']){
            $js .= "var tmp = L.marker([".$info['COORDINATES']."]).addTo(map);\n";
            $js .= "tmp.bindPopup('<b>".$info['HEADING']."</b><br>"
             .$info['POSTCODE']." ".$info['LOCATION'].", ".$info['ADDRESS']."<br>"
        .$info['ESTATE_SIZE']."m²<br>"
        .$info['PRICE']."€<br>"
        .date('Y-m-d H:i', intval($info['PUBLISHED']) / 1000)."<br>"
        .number_format($info['PRICE'] / $info['ESTATE_SIZE'], 2)."€/m²<br>"
        ."<a href=\"".$prefix_willhaben . $info['SEO_URL']."\" target=\"_blank\">Link</a><br>"
        ."<img src=\"".$prefix_mmo . $info['MMO']."\" height=\"200px\">'); \n";
            $js .= 'markers.addLayer(tmp);';
            $cnt += 1;
            }
        }
        catch(Exception $e) {
            print_r('Message: ' .$e->getMessage());
        }
        
    }
}
$js .= "map.addLayer(markers);\n";
echo $js;
?>
</script>
