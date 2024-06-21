<?php

require "db_connect.php";

if(empty($_POST['project_name']))
    return "Error!";

$projectCountryCode = $_POST['project_countrycode'];
$projectName = $_POST['project_name'];
$projectName = str_replace(",", "%2C", $projectName);
$projectName = str_replace(" ", "+", $projectName);

$appstore_api = 'http://itunes.apple.com/search?country='.$projectCountryCode.'&limit=100&entity=software&term='.$projectName;
$data = file_get_contents($appstore_api);
$obj = json_decode($data);

$apps = [];
if ($obj->resultCount > 0) {
    foreach ($obj->results as $key => $item) {
        $app = [];
        $app['bundleId'] = $item->{'bundleId'};
        $app['trackId'] = $item->{'trackId'};
        $app['trackName'] = $item->{'trackName'};
        $app['logo'] = $item->{'artworkUrl60'};
        array_push($apps, $app);
    }
}

print_r(json_encode(['apps' => $apps]));