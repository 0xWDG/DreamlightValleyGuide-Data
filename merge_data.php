<?php

// This script merges all data files into one file

$files = glob("*/*.json");

date_default_timezone_set("Europe/Amsterdam");
$data = array(
    "version" => gmdate('Ymd.Hi'),
    "release" => gmdate("c")
);

foreach ($files as $file) {
    $category = explode("/", $file)[0];

    if ($category == "build" || $category == "assets" || !preg_match("/\.json/", $file)) {
        // Skip the build and assets directories
        // Skip non-JSON files
        continue;
    }

    $f = file_get_contents($file);
    $json = json_decode($f, true);

    $data[$category][] = $json;
}

file_put_contents("build/data.json", json_encode($data));
file_put_contents("build/data_pp.json", json_encode($data, JSON_PRETTY_PRINT));

?>
