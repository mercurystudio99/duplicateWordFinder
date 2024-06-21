<?php

require "db_connect.php";

$data = $_POST;

if(empty($data['country']))
    echo "Oops, country is required!";
if(empty($data['keywords']))
    echo "Oops, keywords are required!";

    $keywords = $data['keywords'];
    $keywords = str_replace(",", "%2C", $keywords);
    $keywords = str_replace(" ", "+", $keywords);
    $countryCode = $data['country'];
    
    $appstore_api = 'http://itunes.apple.com/search?country='.$countryCode.'&limit=100&entity=software&term='.$keywords;
    $data = file_get_contents($appstore_api);
    $obj = json_decode($data);

    $apps = [];
    if ($obj->resultCount > 0) {
        foreach ($obj->results as $key => $item) {
            $app = [];
            $app['sellerUrl'] = '';
            $app['bundleId'] = $item->{'bundleId'};
            $app['trackName'] = $item->{'trackName'};
            $app['position'] = $key;
            array_push($apps, $app);
        }
    }

    $values = '';
    if (count($apps) > 0) {
        foreach ($apps as $app) {
            $sellerUrl = $app['sellerUrl'];
            $bundleId = $app['bundleId'];
            $name = $app['trackName'];
            $position = $app['position'];
            $values .= "('$name', '$bundleId', '$sellerUrl', $position, '$countryCode'),";
        }
        $values = substr($values, 0, strlen($values) - 1);
        $insert = "INSERT INTO apps (`name`, `bundleId`, `sellerUrl`, `position`, `countryCode`) VALUES $values";

        $sql = "DELETE FROM apps WHERE countryCode = '$countryCode'";
        $result = mysqli_query($conn, $sql);
        if($result) {
            mysqli_query($conn, $insert);
        }
    }

header("Location: index.php");